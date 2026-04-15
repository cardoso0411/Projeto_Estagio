<?php

declare(strict_types=1);

$pageTitle = 'Cadastro - Portal de Estagios';
$activePage = 'cadastro';

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="form-shell wide">
        <div class="section-heading">
            <h1>Cadastro no portal</h1>
            <p>Formulario simples para criar conta de aluno ou empresa.</p>
        </div>

        <form class="portal-form two-columns" method="post" action="../actions/register.php">
            <label for="tipo">Perfil</label>
            <select id="tipo" name="tipo">
                <option value="aluno">Aluno</option>
                <option value="empresa">Empresa</option>
            </select>

            <label for="nome">Nome completo</label>
            <input id="nome" name="nome" type="text" placeholder="Digite seu nome" required>

            <label for="ra">RA</label>
            <input id="ra" name="ra" type="text" placeholder="Digite seu RA">

            <label for="curso">Curso</label>
            <input id="curso" name="curso" type="text" placeholder="Digite seu curso">

            <label for="cidade">Cidade</label>
            <input id="cidade" name="cidade" type="text" placeholder="Digite sua cidade">

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" placeholder="Digite seu e-mail" required>

            <label for="senha">Senha</label>
            <input id="senha" name="senha" type="password" placeholder="Crie uma senha" required>

            <div class="full-row actions">
                <button class="button primary" type="submit">Cadastrar</button>
                <a class="button secondary" href="../render/home.php">Voltar</a>
            </div>
        </form>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
