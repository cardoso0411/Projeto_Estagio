<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';
require_login();

$pageTitle = 'Area do Aluno - Portal de Estagios';
$activePage = 'aluno';
$user = current_user();
$applications = [];
$queryError = false;

try {
    $connection = database();
    $statement = $connection->prepare(
        'SELECT c.status, v.titulo, v.empresa
         FROM candidaturas c
         INNER JOIN vagas v ON v.id = c.vaga_id
         WHERE c.usuario_id = ?
         ORDER BY c.created_at DESC'
    );
    $statement->bind_param('i', $user['id']);
    $statement->execute();
    $applications = statement_select_all($statement);
} catch (Throwable $exception) {
    $queryError = true;
}

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="content-section">
        <div class="section-heading">
            <h1>Area do aluno</h1>
            <p>Painel simples conectado ao banco para mostrar os dados do usuario e suas candidaturas.</p>
        </div>

        <div class="grid two">
            <article class="card">
                <h2>Dados do aluno</h2>
                <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome']) ?></p>
                <p><strong>Curso:</strong> <?= htmlspecialchars($user['curso'] ?: 'Nao informado') ?></p>
                <p><strong>RA:</strong> <?= htmlspecialchars($user['ra'] ?: 'Nao informado') ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Cidade:</strong> <?= htmlspecialchars($user['cidade'] ?: 'Nao informada') ?></p>
            </article>

            <article class="card">
                <h2>Resumo</h2>
                <p><strong>Candidaturas realizadas:</strong> <?= count($applications) ?></p>
                <?php
                $aprovadas = count(array_filter($applications, fn($app) => $app['status'] === 'Aprovado'));
                $reprovadas = count(array_filter($applications, fn($app) => $app['status'] === 'Reprovado'));
                $emAnalise = count(array_filter($applications, fn($app) => $app['status'] === 'Em analise'));
                ?>
                <p><strong>Aprovadas:</strong> <?= $aprovadas ?></p>
                <p><strong>Reprovadas:</strong> <?= $reprovadas ?></p>
                <p><strong>Em análise:</strong> <?= $emAnalise ?></p>
                <p><strong>Perfil:</strong> Aluno</p>
            </article>
        </div>

        <div class="content-block">
            <h2>Minhas candidaturas</h2>
            <?php if ($queryError): ?>
                <p>As candidaturas ainda nao puderam ser carregadas. Confira a conexao com o banco.</p>
            <?php elseif (!$applications): ?>
                <p>Voce ainda nao se candidatou para nenhuma vaga. Visite a pagina de vagas para comecar.</p>
            <?php else: ?>
                <table class="portal-table">
                    <thead>
                        <tr>
                            <th>Vaga</th>
                            <th>Empresa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?= htmlspecialchars($application['titulo']) ?></td>
                                <td><?= htmlspecialchars($application['empresa']) ?></td>
                                <td><span class="status status-<?= $application['status'] === 'Aprovado' ? 'ok' : ($application['status'] === 'Reprovado' ? 'danger' : 'warn') ?>"><?= htmlspecialchars($application['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="content-block">
            <h2>Documentos</h2>
            <div class="grid three">
                <article class="doc-card">
                    <h3>Termo de compromisso</h3>
                    <p>Status: Enviado</p>
                    <span class="text-link">Visualizar</span>
                </article>
                <article class="doc-card">
                    <h3>Relatorio parcial</h3>
                    <p>Status: Pendente</p>
                    <span class="text-link">Enviar</span>
                </article>
                <article class="doc-card">
                    <h3>Relatorio final</h3>
                    <p>Status: Aguardando</p>
                    <span class="text-link">Detalhes</span>
                </article>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
