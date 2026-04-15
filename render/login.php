<?php

declare(strict_types=1);

$pageTitle = 'Login - Portal de Estagios';
$activePage = 'login';

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="form-shell">
        <div class="section-heading">
            <h1>Login do portal</h1>
            <p>Acesse sua conta para acompanhar vagas e candidaturas.</p>
        </div>
        <form class="portal-form" method="post" action="../actions/login.php">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" placeholder="Digite seu e-mail" required>

            <label for="senha">Senha</label>
            <input id="senha" name="senha" type="password" placeholder="Digite sua senha" required>

            <div class="actions">
                <button class="button primary" type="submit">Entrar</button>
                <a class="button secondary" href="../render/cadastro.php">Criar conta</a>
            </div>
        </form>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
