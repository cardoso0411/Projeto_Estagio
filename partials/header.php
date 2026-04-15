<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

$pageTitle = $pageTitle ?? 'Portal de Estagios';
$activePage = $activePage ?? '';
$flash = get_flash();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal de estagios da Fatec Itapira">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/portal.css">
</head>
<body>
    <header class="site-header">
        <div class="brand-strip">
            <img src="../img/cps.png" alt="Logo do Centro Paula Souza">
            <img src="../img/fatec.png" alt="Logo da Fatec Itapira">
            <img src="../img/brasao_sp.jpg" alt="Brasao do Estado de Sao Paulo">
        </div>
        <nav class="main-nav">
            <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="../render/home.php">Inicio</a>
            <a class="<?= $activePage === 'vagas' ? 'active' : '' ?>" href="../render/vagas.php">Vagas</a>
            <?php if ($user): ?>
                <a class="<?= $activePage === 'aluno' ? 'active' : '' ?>" href="../render/aluno.php">Area do Aluno</a>
                <a href="../actions/logout.php">Sair</a>
            <?php else: ?>
                <a class="<?= $activePage === 'login' ? 'active' : '' ?>" href="../render/login.php">Login</a>
                <a class="<?= $activePage === 'cadastro' ? 'active' : '' ?>" href="../render/cadastro.php">Cadastro</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>
