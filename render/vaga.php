<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

$pageTitle = 'Detalhe da Vaga - Portal de Estagios';
$activePage = 'vagas';
$job = null;
$queryError = false;
$vagaId = (int) ($_GET['id'] ?? 0);

if ($vagaId > 0) {
    try {
        $connection = database();
        $statement = $connection->prepare('SELECT * FROM vagas WHERE id = ? LIMIT 1');
        $statement->bind_param('i', $vagaId);
        $statement->execute();
        $job = statement_select_one($statement);
    } catch (Throwable $exception) {
        $queryError = true;
    }
}

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="content-section">
        <?php if ($queryError): ?>
            <article class="card empty-state">
                <h1>Banco ainda nao configurado</h1>
                <p>Verifique `config/database.php` e importe `database/estagio.sql` para carregar os detalhes da vaga.</p>
            </article>
        <?php elseif (!$job): ?>
            <article class="card empty-state">
                <h1>Vaga nao encontrada</h1>
                <p>A vaga solicitada nao foi localizada. Volte para a listagem e selecione outra opcao.</p>
            </article>
        <?php else: ?>
            <div class="detail-layout">
                <article class="detail-card">
                    <span class="badge"><?= htmlspecialchars($job['curso']) ?></span>
                    <h1><?= htmlspecialchars($job['titulo']) ?></h1>
                    <p><strong>Empresa:</strong> <?= htmlspecialchars($job['empresa']) ?></p>
                    <p><strong>Cidade:</strong> <?= htmlspecialchars($job['cidade']) ?></p>
                    <p><strong>Bolsa:</strong> R$ <?= number_format((float) $job['bolsa'], 2, ',', '.') ?></p>
                    <p><strong>Carga horaria:</strong> <?= htmlspecialchars($job['carga_horaria']) ?></p>
                    <p><strong>Modalidade:</strong> <?= htmlspecialchars($job['modalidade']) ?></p>
                    <p><strong>Descricao:</strong> <?= htmlspecialchars($job['descricao']) ?></p>
                    <div class="actions">
                        <?php if (is_logged_in()): ?>
                            <form method="post" action="../actions/apply.php">
                                <input type="hidden" name="vaga_id" value="<?= (int) $job['id'] ?>">
                                <button class="button primary" type="submit">Candidatar-se</button>
                            </form>
                        <?php else: ?>
                            <a class="button primary" href="../render/login.php">Entrar para se candidatar</a>
                        <?php endif; ?>
                        <a class="button secondary" href="../render/vagas.php">Voltar para vagas</a>
                    </div>
                </article>

                <aside class="sidebar-card">
                    <h2>Requisitos</h2>
                    <p><?= nl2br(htmlspecialchars($job['requisitos'])) ?></p>

                    <h2>Beneficios</h2>
                    <p><?= nl2br(htmlspecialchars($job['beneficios'] ?? 'Nao informado')) ?></p>
                </aside>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
