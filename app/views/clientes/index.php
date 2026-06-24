<?php
/**
 * Listagem de clientes.
 * 
 * @var array  $clientes
 * @var string $busca
 * @var string $status
 * @var int    $pagina
 * @var int    $totalPaginas
 * @var int    $total
 * @var ?string $flashSuccess
 */

$statusLabels = [
    'novo'          => 'Novo',
    'em_contato'    => 'Em contato',
    'agendado'      => 'Agendado',
    'atendido'      => 'Atendido',
    'perdido'       => 'Perdido',
    'inativo'       => 'Inativo',
];
?>

<div class="page-header">
    <div>
        <h1>Clientes / Leads</h1>
        <div class="subtitulo"><?= $total ?> registro(s) encontrado(s)</div>
    </div>
    <a href="/clientes/novo" class="btn btn-primary">
        <i data-lucide="plus"></i>
        Novo cliente
    </a>
</div>

<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif ;?>

<form method="GET" action="/clientes" class="filter-bar">
    <input 
        type="search"
        name="busca"
        value="<?= htmlspecialchars($busca) ?>"
        placeholder="Buscar por nome, e-mail ou telefone..."
    >
    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach ($statusLabels as $key => $label): ?>
            <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>>
                <?= $label ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-secondary">
        <i data-lucide="filter"></i>
        Filtrar
    </button>
    <?php if ($busca || $status): ?>
        <a href="/clientes" class="btn btn-secondary">
            <i data-lucide="x"></i>
            Limpar
        </a>
    <?php endif; ?>
</form>

<?php if (empty($clientes)): ?>
    <div class="table-wrapper">
        <div class="empty-state">
            <h3>Nenhum cliente encontrado</h3>
            <p>Ajuste os filtros ou cadstr um novo cliente para começar.</p>
        </div>
    </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>Origem</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td>
                                <a href="/clientes/<?= $cliente['id'] ?>" class="row-link">
                                    <?= htmlspecialchars($cliente['nome']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($cliente['email']): ?>
                                    <?= htmlspecialchars($cliente['email']) ?><br>
                                <?php endif; ?>
                                <?php if ($cliente['telefone']): ?>
                                    <small><?= htmlspecialchars($cliente['telefone']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($cliente['origem'] ?? '-') ?></td>
                            <td>
                                <span class="status-badge status-<?= $cliente['status'] ?>">
                                    <?= $statusLabels[$cliente['status']] ?? $cliente['status'] ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($cliente['criado_em'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPaginas >1): ?>
            <nav class="pagination">
                <?php
                //Constroi a URL preservando os fitros
                $queryBase = http_build_query(array_filter([
                    'busca'     => $busca,
                    'status'    => $status,
                ]));
                $sep = $queryBase ? '&' : '';
                ?>
                <?php if ($pagina > 1): ?>
                    <a href="?<?= $queryBase . $sep ?>pagina<?= $pagina - 1 ?>">‹ Anterior</a>
                <?php else: ?>
                    <span class="disabled">‹ Anterior</span>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                    <?php if ($p === $pagina): ?>
                        <span class="current"><?= $p ?></span>
                    <?php else: ?>
                        <a href="?<?= $queryBase . $sep ?>pagina<?= $p ?>"><?= $p ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagina < $totalPaginas): ?>
                    <a href="?<?= $queryBase . $sep ?>pagina<?= $pagina + 1 ?>">Próxima ›</a>
                <?php else: ?>
                    <span class="disabled">Próxima ›</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
<?php endif; ?>