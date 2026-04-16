<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

require_company();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/empresa.php');
}

$user = current_user();
$candidaturaId = (int) ($_POST['candidatura_id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if ($candidaturaId <= 0 || !in_array($status, ['Aprovado', 'Reprovado'])) {
    set_flash('error', 'Dados inválidos para atualização da candidatura.');
    redirect_to('../render/empresa.php');
}

try {
    $connection = database();

    // Verificar se a candidatura pertence a uma vaga da empresa
    $statement = $connection->prepare(
        'SELECT c.id FROM candidaturas c
         INNER JOIN vagas v ON v.id = c.vaga_id
         WHERE c.id = ? AND v.empresa = ?'
    );
    $statement->bind_param('is', $candidaturaId, $user['nome']);
    $statement->execute();
    $candidatura = statement_select_one($statement);

    if (!$candidatura) {
        set_flash('error', 'Candidatura não encontrada ou não pertence à sua empresa.');
        redirect_to('../render/empresa.php');
    }

    // Atualizar status
    $statement = $connection->prepare('UPDATE candidaturas SET status = ? WHERE id = ?');
    $statement->bind_param('si', $status, $candidaturaId);
    $statement->execute();

    set_flash('success', "Candidatura {$status} com sucesso.");
} catch (Throwable $exception) {
    set_flash('error', 'Não foi possível atualizar o status da candidatura.');
}

redirect_to('../render/empresa.php');
