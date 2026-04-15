<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

$pageTitle = 'Portal de Estagios - Fatec Itapira';
$activePage = 'home';
$featuredJobs = [];

try {
    $result = database()->query("SELECT id, titulo, empresa, bolsa, cidade, curso FROM vagas WHERE status = 'aberta' ORDER BY created_at DESC LIMIT 3");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $featuredJobs[] = $row;
        }
    }
} catch (Throwable $exception) {
    $featuredJobs = [];
}

require_once __DIR__ . '/../partials/header.php';
?>
<main>
    <section class="hero">
        <div class="hero-copy">
            <span class="tag">Projeto Academico - 2o semestre</span>
            <h1>Portal de Estagios da Fatec Itapira</h1>
            <p>Um portal simples para aproximar alunos e empresas, divulgar vagas e acompanhar o inicio do processo de estagio.</p>
            <div class="actions">
                <a class="button primary" href="../render/vagas.php">Ver vagas</a>
                <?php if (is_logged_in()): ?>
                    <a class="button secondary" href="../render/aluno.php">Ir para a area do aluno</a>
                <?php else: ?>
                    <a class="button secondary" href="../render/login.php">Entrar no portal</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-panel">
            <img src="../img/Capturar.PNG" alt="Imagem ilustrativa do portal">
        </div>
    </section>

    <section class="content-section">
        <div class="section-heading">
            <h2>Sobre o portal</h2>
            <p>Este portal centraliza vagas, candidaturas e o acompanhamento inicial da documentacao de estagio.</p>
        </div>
        <div class="grid three">
            <article class="card">
                <h3>Consulta de vagas</h3>
                <p>Os alunos podem buscar oportunidades com curso, cidade, modalidade e valor da bolsa.</p>
            </article>
            <article class="card">
                <h3>Cadastro e login</h3>
                <p>O portal ja possui uma base para criar conta, autenticar usuario e manter sessao ativa.</p>
            </article>
            <article class="card">
                <h3>Candidatura simples</h3>
                <p>Com a conta ativa, o aluno pode se candidatar e acompanhar o status da vaga escolhida.</p>
            </article>
        </div>
    </section>

    <section class="content-section accent">
        <div class="section-heading">
            <h2>Vagas em destaque</h2>
            <p>As vagas abaixo sao carregadas do banco de dados quando ele estiver configurado.</p>
        </div>
        <div class="grid three">
            <?php if ($featuredJobs): ?>
                <?php foreach ($featuredJobs as $job): ?>
                    <article class="job-card">
                        <span class="badge"><?= htmlspecialchars($job['curso']) ?></span>
                        <h3><?= htmlspecialchars($job['titulo']) ?></h3>
                        <p>Empresa: <?= htmlspecialchars($job['empresa']) ?></p>
                        <p>Bolsa: R$ <?= number_format((float) $job['bolsa'], 2, ',', '.') ?></p>
                        <p>Cidade: <?= htmlspecialchars($job['cidade']) ?></p>
                        <a class="text-link" href="../render/vaga.php?id=<?= (int) $job['id'] ?>">Saiba mais</a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <article class="card empty-state">
                    <h3>Banco ainda nao conectado</h3>
                    <p>Importe o arquivo `database/estagio.sql` no banco `estagio` para ver as vagas carregadas dinamicamente.</p>
                </article>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
