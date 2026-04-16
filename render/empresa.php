<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';
require_company();

$pageTitle = 'Area da Empresa - Portal de Estagios';
$activePage = 'empresa';
$user = current_user();
$vacancies = [];
$queryError = false;

try {
    $connection = database();
    $statement = $connection->prepare(
        'SELECT v.id, v.titulo, v.curso, v.cidade, v.bolsa, v.carga_horaria, v.modalidade, v.status, v.created_at, COUNT(c.id) AS candidatos
         FROM vagas v
         LEFT JOIN candidaturas c ON c.vaga_id = v.id
         WHERE v.empresa = ?
         GROUP BY v.id, v.titulo, v.curso, v.cidade, v.bolsa, v.carga_horaria, v.modalidade, v.status, v.created_at
         ORDER BY v.created_at DESC'
    );
    $statement->bind_param('s', $user['nome']);
    $statement->execute();
    $vacancies = statement_select_all($statement);
} catch (Throwable $exception) {
    $queryError = true;
}

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="content-section">
        <div class="section-heading">
            <h1>Área da empresa</h1>
            <p>Cadastre vagas de estágio, acompanhe suas publicações e veja quantos candidatos foram recebidos.</p>
        </div>

        <div class="grid two">
            <article class="card">
                <h2>Dados da empresa</h2>
                <p><strong>Empresa:</strong> <?= htmlspecialchars($user['nome']) ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Cidade:</strong> <?= htmlspecialchars($user['cidade'] ?: 'Nao informada') ?></p>
                <p><strong>Perfil:</strong> Empresa</p>
            </article>

            <article class="card">
                <h2>Publicar nova vaga</h2>
                <form class="portal-form" method="post" action="../actions/create_vaga.php">
                    <label for="titulo">Titulo</label>
                    <input id="titulo" name="titulo" type="text" required>

                    <label for="curso">Curso</label>
                    <input id="curso" name="curso" type="text" required>

                    <label for="cidade">Cidade</label>
                    <input id="cidade" name="cidade" type="text" required>

                    <label for="bolsa">Bolsa</label>
                    <input id="bolsa" name="bolsa" type="number" step="0.01" min="0" required>

                    <label for="carga_horaria">Carga horária</label>
                    <input id="carga_horaria" name="carga_horaria" type="text" required>

                    <label for="modalidade">Modalidade</label>
                    <select id="modalidade" name="modalidade" required>
                        <option value="">Selecione</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Hibrido">Hibrido</option>
                        <option value="Remoto">Remoto</option>
                    </select>

                    <label for="descricao">Descricao</label>
                    <textarea id="descricao" name="descricao" rows="4" required></textarea>

                    <label for="requisitos">Requisitos</label>
                    <textarea id="requisitos" name="requisitos" rows="3" required></textarea>

                    <label for="beneficios">Beneficios</label>
                    <textarea id="beneficios" name="beneficios" rows="2"></textarea>

                    <div class="full-row actions">
                        <button class="button primary" type="submit">Cadastrar vaga</button>
                    </div>
                </form>
            </article>
        </div>

        <div class="content-block">
            <h2>Minhas vagas publicadas</h2>
            <?php if ($queryError): ?>
                <article class="card empty-state">
                    <h3>Erro ao carregar vagas</h3>
                    <p>Não foi possível consultar suas vagas no banco. Verifique a conexão e tente novamente.</p>
                </article>
            <?php elseif (!$vacancies): ?>
                <article class="card empty-state">
                    <h3>Sem vagas publicadas</h3>
                    <p>Use o formulário acima para cadastrar sua primeira vaga de estágio.</p>
                </article>
            <?php else: ?>
                <div class="grid two">
                    <?php foreach ($vacancies as $vacancy): ?>
                        <article class="job-card">
                            <span class="badge"><?= htmlspecialchars($vacancy['curso']) ?></span>
                            <h3><?= htmlspecialchars($vacancy['titulo']) ?></h3>
                            <p>Bolsa: R$ <?= number_format((float) $vacancy['bolsa'], 2, ',', '.') ?></p>
                            <p>Cidade: <?= htmlspecialchars($vacancy['cidade']) ?></p>
                            <p>Modalidade: <?= htmlspecialchars($vacancy['modalidade']) ?></p>
                            <p>Candidatos: <?= (int) $vacancy['candidatos'] ?></p>
                            <a class="button secondary small" href="../render/vaga.php?id=<?= (int) $vacancy['id'] ?>">Ver detalhes</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
