<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

$pageTitle = 'Vagas - Portal de Estagios';
$activePage = 'vagas';
$filters = [
    'curso' => trim($_GET['curso'] ?? ''),
    'cidade' => trim($_GET['cidade'] ?? ''),
    'modalidade' => trim($_GET['modalidade'] ?? ''),
];
$jobs = [];
$queryError = false;

try {
    $connection = database();
    $sql = "SELECT id, titulo, empresa, curso, cidade, bolsa, carga_horaria, modalidade FROM vagas WHERE status = 'aberta'";
    $types = '';
    $params = [];

    if ($filters['curso'] !== '') {
        $sql .= ' AND curso = ?';
        $types .= 's';
        $params[] = $filters['curso'];
    }

    if ($filters['cidade'] !== '') {
        $sql .= ' AND cidade LIKE ?';
        $types .= 's';
        $params[] = '%' . $filters['cidade'] . '%';
    }

    if ($filters['modalidade'] !== '') {
        $sql .= ' AND modalidade = ?';
        $types .= 's';
        $params[] = $filters['modalidade'];
    }

    $sql .= ' ORDER BY created_at DESC';
    $statement = $connection->prepare($sql);

    if ($types !== '') {
        $statement->bind_param($types, ...$params);
    }

    $statement->execute();
    $jobs = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $exception) {
    $queryError = true;
}

require_once __DIR__ . '/../partials/header.php';
?>
<main class="page-main">
    <section class="content-section">
        <div class="section-heading">
            <h1>Vagas de estagio</h1>
            <p>Agora a listagem consulta os dados do banco `estagio` quando a conexao estiver pronta.</p>
        </div>

        <form class="filters" method="get" action="../render/vagas.php">
            <div>
                <label for="filtro-curso">Curso</label>
                <select id="filtro-curso" name="curso">
                    <option value="">Todos</option>
                    <option value="ADS" <?= $filters['curso'] === 'ADS' ? 'selected' : '' ?>>ADS</option>
                    <option value="Gestao Empresarial" <?= $filters['curso'] === 'Gestao Empresarial' ? 'selected' : '' ?>>Gestao Empresarial</option>
                </select>
            </div>
            <div>
                <label for="filtro-cidade">Cidade</label>
                <input id="filtro-cidade" name="cidade" type="text" value="<?= htmlspecialchars($filters['cidade']) ?>" placeholder="Ex: Itapira">
            </div>
            <div>
                <label for="filtro-modalidade">Modalidade</label>
                <select id="filtro-modalidade" name="modalidade">
                    <option value="">Todas</option>
                    <option value="Presencial" <?= $filters['modalidade'] === 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                    <option value="Hibrido" <?= $filters['modalidade'] === 'Hibrido' ? 'selected' : '' ?>>Hibrido</option>
                    <option value="Remoto" <?= $filters['modalidade'] === 'Remoto' ? 'selected' : '' ?>>Remoto</option>
                </select>
            </div>
            <div class="filter-action">
                <button class="button primary" type="submit">Filtrar</button>
            </div>
        </form>

        <div class="grid two">
            <?php if ($queryError): ?>
                <article class="card empty-state">
                    <h3>Conexao pendente</h3>
                    <p>Importe o script `database/estagio.sql` e ajuste as credenciais em `config/database.php` para liberar as vagas.</p>
                </article>
            <?php elseif (!$jobs): ?>
                <article class="card empty-state">
                    <h3>Nenhuma vaga encontrada</h3>
                    <p>Tente alterar os filtros ou cadastre novas vagas no banco para popular a listagem.</p>
                </article>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                    <article class="job-card">
                        <span class="badge"><?= htmlspecialchars($job['curso']) ?></span>
                        <h3><?= htmlspecialchars($job['titulo']) ?></h3>
                        <p>Empresa: <?= htmlspecialchars($job['empresa']) ?></p>
                        <p>Bolsa: R$ <?= number_format((float) $job['bolsa'], 2, ',', '.') ?></p>
                        <p>Carga horaria: <?= htmlspecialchars($job['carga_horaria']) ?></p>
                        <p>Modalidade: <?= htmlspecialchars($job['modalidade']) ?></p>
                        <a class="button secondary small" href="../render/vaga.php?id=<?= (int) $job['id'] ?>">Detalhes</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
