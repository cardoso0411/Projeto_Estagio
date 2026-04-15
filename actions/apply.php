<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('../render/vagas.php');
}

$vagaId = (int) ($_POST['vaga_id'] ?? 0);
$user = current_user();

if ($vagaId <= 0 || !$user) {
    set_flash('error', 'Nao foi possivel registrar a candidatura.');
    redirect_to('../render/vagas.php');
}

try {
    $connection = database();
    $check = $connection->prepare('SELECT id FROM candidaturas WHERE usuario_id = ? AND vaga_id = ? LIMIT 1');
    $check->bind_param('ii', $user['id'], $vagaId);
    $check->execute();
    $existing = $check->get_result()->fetch_assoc();

    if ($existing) {
        set_flash('error', 'Voce ja se candidatou para essa vaga.');
        redirect_to('../render/aluno.php');
    }

    $status = 'Em analise';
    $statement = $connection->prepare('INSERT INTO candidaturas (usuario_id, vaga_id, status) VALUES (?, ?, ?)');
    $statement->bind_param('iis', $user['id'], $vagaId, $status);
    $statement->execute();

    set_flash('success', 'Candidatura enviada com sucesso.');
    redirect_to('../render/aluno.php');
} catch (Throwable $exception) {
    set_flash('error', 'Nao foi possivel registrar sua candidatura agora.');
    redirect_to('../render/vagas.php');
}
