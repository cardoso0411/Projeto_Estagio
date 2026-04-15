<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/cadastro.php');
}

$tipo = trim($_POST['tipo'] ?? 'aluno');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$curso = trim($_POST['curso'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$ra = trim($_POST['ra'] ?? '');

if ($nome === '' || $email === '' || $senha === '') {
    set_flash('error', 'Preencha nome, e-mail e senha para concluir o cadastro.');
    redirect_to('../render/cadastro.php');
}

try {
    $connection = database();
    $check = $connection->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $check->bind_param('s', $email);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();

    if ($existing) {
        set_flash('error', 'Ja existe um usuario cadastrado com esse e-mail.');
        redirect_to('../render/cadastro.php');
    }

    $passwordHash = password_hash($senha, PASSWORD_DEFAULT);
    $statement = $connection->prepare(
        'INSERT INTO usuarios (nome, email, senha_hash, tipo, curso, cidade, ra) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $statement->bind_param('sssssss', $nome, $email, $passwordHash, $tipo, $curso, $cidade, $ra);
    $statement->execute();

    set_flash('success', 'Cadastro realizado com sucesso. Agora voce pode fazer login.');
} catch (Throwable $exception) {
    set_flash('error', 'Nao foi possivel cadastrar agora. Verifique a estrutura do banco de dados.');
}

redirect_to('../render/login.php');
