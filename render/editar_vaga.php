<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';
require_company();

$pageTitle = 'Editar Vaga - Portal de Estagios';
$activePage = 'empresa';
$user = current_user();
$job = null;
$queryError = false;
$vagaId = (int) ($_GET['id'] ?? 0);

if ($vagaId > 0) {
    try {
        $connection = database();
        $statement = $connection->prepare('SELECT * FROM vagas WHERE id = ? AND empresa = ? LIMIT 1');
        $statement->bind_param('is', $vagaId, $user['nome']);
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
                <h1>Erro ao carregar a vaga</h1>
                <p>Não foi possível carregar os dados dessa vaga. Tente novamente mais tarde.</p>
            </article>
        <?php elseif (!$job): ?>
            <article class="card empty-state">
                <h1>Vaga não encontrada</h1>
                <p>Essa vaga não foi encontrada ou não pertence à sua empresa.</p>
                <a class="button secondary" href="../render/empresa.php">Voltar para a área da empresa</a>
            </article>
        <?php else: ?>
            <div class="section-heading">
                <h1>Editar vaga</h1>
                <p>Atualize os detalhes da vaga antes de publicar novamente.</p>
            </div>

            <article class="card">
                <form class="portal-form" method="post" action="../actions/update_vaga.php">
                    <input type="hidden" name="vaga_id" value="<?= (int) $job['id'] ?>">

                    <label for="titulo">Titulo</label>
                    <input id="titulo" name="titulo" type="text" value="<?= htmlspecialchars($job['titulo']) ?>" required>

                    <label for="curso">Curso</label>
                    <input id="curso" name="curso" type="text" value="<?= htmlspecialchars($job['curso']) ?>" required>

                    <label for="cidade">Cidade</label>
                    <input id="cidade" name="cidade" type="text" value="<?= htmlspecialchars($job['cidade']) ?>" required>

                    <label for="bolsa">Bolsa</label>
                    <input id="bolsa" name="bolsa" type="number" step="0.01" min="0" value="<?= htmlspecialchars($job['bolsa']) ?>" required>

                    <label for="carga_horaria">Carga horária</label>
                    <input id="carga_horaria" name="carga_horaria" type="text" value="<?= htmlspecialchars($job['carga_horaria']) ?>" required>

                    <label for="modalidade">Modalidade</label>
                    <select id="modalidade" name="modalidade" required>
                        <option value="Presencial" <?= $job['modalidade'] === 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                        <option value="Hibrido" <?= $job['modalidade'] === 'Hibrido' ? 'selected' : '' ?>>Hibrido</option>
                        <option value="Remoto" <?= $job['modalidade'] === 'Remoto' ? 'selected' : '' ?>>Remoto</option>
                    </select>

                    <label for="descricao">Descricao</label>
                    <textarea id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($job['descricao']) ?></textarea>

                    <label for="requisitos">Requisitos</label>
                    <textarea id="requisitos" name="requisitos" rows="3" required><?= htmlspecialchars($job['requisitos']) ?></textarea>

                    <label for="beneficios">Beneficios</label>
                    <textarea id="beneficios" name="beneficios" rows="2"><?= htmlspecialchars($job['beneficios'] ?? '') ?></textarea>

                    <div class="full-row actions">
                        <button class="button primary" type="submit">Atualizar vaga</button>
                        <a class="button secondary" href="../render/empresa.php">Cancelar</a>
                    </div>
                </form>
            </article>
        <?php endif; ?>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
