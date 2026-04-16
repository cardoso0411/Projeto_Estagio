<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

require_company();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/empresa.php');
}

$user = current_user();
$vagaId = (int) ($_POST['vaga_id'] ?? 0);

if ($vagaId <= 0) {
    set_flash('error', 'Vaga invalida.');
    redirect_to('../render/empresa.php');
}

try {
    $connection = database();
    $statement = $connection->prepare('DELETE FROM vagas WHERE id = ? AND empresa = ?');
    $empresa = $user['nome'];
    $statement->bind_param('is', $vagaId, $empresa);
    $statement->execute();

    if ($statement->affected_rows > 0) {
        set_flash('success', 'Vaga excluida com sucesso.');
    } else {
        set_flash('error', 'Nao foi possivel excluir a vaga.');
    }
} catch (Throwable $exception) {
    set_flash('error', 'Erro ao excluir a vaga.');
}

redirect_to('../render/empresa.php');
