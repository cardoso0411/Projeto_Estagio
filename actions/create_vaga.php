<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

require_company();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/empresa.php');
}

$user = current_user();

$titulo = trim($_POST['titulo'] ?? '');
$curso = trim($_POST['curso'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$bolsa = trim($_POST['bolsa'] ?? '');
$cargaHoraria = trim($_POST['carga_horaria'] ?? '');
$modalidade = trim($_POST['modalidade'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$requisitos = trim($_POST['requisitos'] ?? '');
$beneficios = trim($_POST['beneficios'] ?? '');

if ($titulo === '' || $curso === '' || $cidade === '' || $bolsa === '' || $cargaHoraria === '' || $modalidade === '' || $descricao === '' || $requisitos === '') {
    set_flash('error', 'Preencha todos os campos obrigatorios para cadastrar a vaga.');
    redirect_to('../render/empresa.php');
}

try {
    $connection = database();
    $statement = $connection->prepare(
        'INSERT INTO vagas (titulo, empresa, curso, cidade, bolsa, carga_horaria, modalidade, descricao, requisitos, beneficios, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    if ($statement === false) {
        throw new RuntimeException('Falha ao preparar o cadastro da vaga.');
    }

    $empresa = $user['nome'];
    $status = 'aberta';
    $statement->bind_param('sssdsssssss', $titulo, $empresa, $curso, $cidade, $bolsa, $cargaHoraria, $modalidade, $descricao, $requisitos, $beneficios, $status);
    $statement->execute();

    set_flash('success', 'Vaga cadastrada com sucesso.');
} catch (Throwable $exception) {
    set_flash('error', 'Nao foi possivel cadastrar a vaga agora.');
}

redirect_to('../render/empresa.php');
