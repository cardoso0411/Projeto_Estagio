<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';
require_company();

$pageTitle = 'Candidatos - Portal de Estagios';
$activePage = 'empresa';
$user = current_user();
$vagaId = (int) ($_GET['vaga_id'] ?? 0);
$job = null;
$candidates = [];
$queryError = false;

if ($vagaId > 0) {
    try {
        $connection = database();

        // Verificar se a vaga pertence à empresa
        $statement = $connection->prepare('SELECT * FROM vagas WHERE id = ? AND empresa = ? LIMIT 1');
        $statement->bind_param('is', $vagaId, $user['nome']);
        $statement->execute();
        $job = statement_select_one($statement);

        if ($job) {
            // Buscar candidatos
            $statement = $connection->prepare(
                'SELECT u.id, u.nome, u.email, u.curso, u.ra, u.cidade, c.status, c.created_at
                 FROM candidaturas c
                 INNER JOIN usuarios u ON u.id = c.usuario_id
                 WHERE c.vaga_id = ?
                 ORDER BY c.created_at DESC'
            );
            $statement->bind_param('i', $vagaId);
            $statement->execute();
            $candidates = statement_select_all($statement);
        }
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
                <h1>Erro ao carregar candidatos</h1>
                <p>Não foi possível consultar os candidatos desta vaga. Tente novamente mais tarde.</p>
            </article>
        <?php elseif (!$job): ?>
            <article class="card empty-state">
                <h1>Vaga não encontrada</h1>
                <p>Esta vaga não foi encontrada ou não pertence à sua empresa.</p>
                <a class="button secondary" href="../render/empresa.php">Voltar para a área da empresa</a>
            </article>
        <?php else: ?>
            <div class="section-heading">
                <h1>Candidatos para: <?= htmlspecialchars($job['titulo']) ?></h1>
                <p>Total de candidatos: <?= count($candidates) ?></p>
            </div>

            <?php if (!$candidates): ?>
                <article class="card empty-state">
                    <h3>Nenhum candidato ainda</h3>
                    <p>Esta vaga ainda não recebeu candidaturas.</p>
                </article>
            <?php else: ?>
                <div class="content-block">
                    <table class="portal-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Curso</th>
                                <th>RA</th>
                                <th>Cidade</th>
                                <th>Data da Candidatura</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $candidate): ?>
                                <tr>
                                    <td><?= htmlspecialchars($candidate['nome']) ?></td>
                                    <td><?= htmlspecialchars($candidate['email']) ?></td>
                                    <td><?= htmlspecialchars($candidate['curso'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($candidate['ra'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($candidate['cidade'] ?: 'N/A') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($candidate['created_at'])) ?></td>
                                    <td><span class="status status-<?= $candidate['status'] === 'Aprovado' ? 'ok' : ($candidate['status'] === 'Reprovado' ? 'danger' : 'warn') ?>"><?= htmlspecialchars($candidate['status']) ?></span></td>
                                    <td>
                                        <div class="actions" style="margin: 0; gap: 4px;">
                                            <?php if ($candidate['status'] === 'Em analise'): ?>
                                                <form method="post" action="../actions/update_candidatura.php" style="display:inline-block; margin:0;">
                                                    <input type="hidden" name="candidatura_id" value="<?= (int) $candidate['id'] ?>">
                                                    <input type="hidden" name="status" value="Aprovado">
                                                    <button class="button primary small" type="submit">Aprovar</button>
                                                </form>
                                                <form method="post" action="../actions/update_candidatura.php" style="display:inline-block; margin:0;">
                                                    <input type="hidden" name="candidatura_id" value="<?= (int) $candidate['id'] ?>">
                                                    <input type="hidden" name="status" value="Reprovado">
                                                    <button class="button danger small" type="submit">Reprovar</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-link" style="color: var(--muted); font-size: 0.9em;">Decisão tomada</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="actions" style="margin-top: 24px;">
                <a class="button secondary" href="../render/empresa.php">Voltar para a área da empresa</a>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
