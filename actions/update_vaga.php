<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

require_company();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/empresa.php');
}

$user = current_user();

$vagaId = (int) ($_POST['vaga_id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$curso = trim($_POST['curso'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$bolsa = trim($_POST['bolsa'] ?? '');
$cargaHoraria = trim($_POST['carga_horaria'] ?? '');
$modalidade = trim($_POST['modalidade'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$requisitos = trim($_POST['requisitos'] ?? '');
$beneficios = trim($_POST['beneficios'] ?? '');

if ($vagaId <= 0 || $titulo === '' || $curso === '' || $cidade === '' || $bolsa === '' || $cargaHoraria === '' || $modalidade === '' || $descricao === '' || $requisitos === '') {
    set_flash('error', 'Preencha todos os campos obrigatorios para atualizar a vaga.');
    redirect_to('../render/editar_vaga.php?id=' . $vagaId);
}

try {
    $connection = database();
    $statement = $connection->prepare(
        'UPDATE vagas
         SET titulo = ?, curso = ?, cidade = ?, bolsa = ?, carga_horaria = ?, modalidade = ?, descricao = ?, requisitos = ?, beneficios = ?
         WHERE id = ? AND empresa = ?'
    );

    if ($statement === false) {
        throw new RuntimeException('Falha ao preparar a atualizacao da vaga.');
    }

    $empresa = $user['nome'];
    $statement->bind_param('sssdsssssis', $titulo, $curso, $cidade, $bolsa, $cargaHoraria, $modalidade, $descricao, $requisitos, $beneficios, $vagaId, $empresa);
    $statement->execute();

    set_flash('success', 'Vaga atualizada com sucesso.');
} catch (Throwable $exception) {
    set_flash('error', 'Nao foi possivel atualizar a vaga agora.');
}

redirect_to('../render/empresa.php');
