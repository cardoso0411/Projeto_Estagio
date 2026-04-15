<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/login.php');
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    set_flash('error', 'Informe e-mail e senha para entrar.');
    redirect_to('../render/login.php');
}

try {
    $connection = database();
    $statement = $connection->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
    $statement->bind_param('s', $email);
    $statement->execute();
    $user = statement_select_one($statement);

    if (!$user || !password_verify($senha, $user['senha_hash'])) {
        set_flash('error', 'E-mail ou senha invalidos.');
        redirect_to('../render/login.php');
    }

    login_user($user);
    set_flash('success', 'Login realizado com sucesso.');
    redirect_to('../render/aluno.php');
} catch (Throwable $exception) {
    set_flash('error', 'Nao foi possivel fazer login. Verifique a conexao com o banco.');
    redirect_to('../render/login.php');
}
