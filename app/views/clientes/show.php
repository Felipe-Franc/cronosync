<?php
/**
 * Detalhes de um cliente + histórico de agendamentos.
 *
 * @var array       $cliente
 * @var array       $historico
 * @var ?string     $flashSuccess
 */

$statusLabels = [
    'novo' => 'Novo', 'em_contato' => 'Em contato', 'agendado' => 'Agendado',
    'atendido' => 'Atendido', 'perdido' => 'Perdido', 'inativo' => 'Inativo',
];
$agendStatusLabels = [
    'agendado' => 'Agendado', 'confirmado' => 'Confirmado',
    'compareceu' => 'Compareceu', 'nao_compareceu' => 'Não compareceu',
    'cancelado' => 'Cancelado', 'reagendado' => 'Reagendado',
];
?>

<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($cliente['nome']) ?></h1>
        <div class="subtitulo">
            <a href="/clientes" style="color:inherit;">← Voltar para a lista</a>
        </div>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="/clientes/<?= $cliente['id'] ?>/editar" class="btn btn-secondary">
            <i data-lucide="edit-2"></i> Editar
        </a>
        <?php if ($cliente['status'] !== 'inativo'): ?>
            <form method="POST"
                  action="/clientes/<?= $cliente['id'] ?>/excluir"
                  onsubmit="return confirm('Marcar este cliente como inativo?');"
                  style="display:inline;">
                <button type="submit" class="btn btn-danger">
                    <i data-lucide="archive"></i> Arquivar
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>

<div class="form-card" style="margin-bottom: var(--gap-lg);">
    <div class="form-grid">
        <div class="form-group">
            <label>Status</label>
            <div>
                <span class="status-badge status-<?= $cliente['status'] ?>">
                    <?= $statusLabels[$cliente['status']] ?? $cliente['status'] ?>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label>Origem</label>
            <div><?= htmlspecialchars($cliente['origem'] ?? '—') ?></div>
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <div><?= htmlspecialchars($cliente['email'] ?? '—') ?></div>
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <div><?= htmlspecialchars($cliente['telefone'] ?? '—') ?></div>
        </div>
        <div class="form-group">
            <label>WhatsApp</label>
            <div><?= htmlspecialchars($cliente['whatsapp'] ?? '—') ?></div>
        </div>
        <div class="form-group">
            <label>Cadastrado em</label>
            <div><?= date('d/m/Y H:i', strtotime($cliente['criado_em'])) ?></div>
        </div>
        <?php if ($cliente['observacoes']): ?>
            <div class="form-group full-width">
                <label>Observações</label>
                <div style="white-space:pre-wrap;"><?= htmlspecialchars($cliente['observacoes']) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<h2 style="font-size:1.1rem; color:var(--azul-marinho); margin-bottom: var(--gap-sm);">
    Histórico de agendamentos
</h2>

<?php if (empty($historico)): ?>
    <div class="table-wrapper">
        <div class="empty-state">
            <p>Este cliente ainda não tem agendamentos.</p>
        </div>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $a): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($a['data'])) ?></td>
                        <td><?= substr($a['hora_inicio'], 0, 5) ?></td>
                        <td><?= htmlspecialchars($a['servico_nome'] ?? '—') ?></td>
                        <td>
                            <span class="status-badge status-<?= $a['status'] ?>">
                                <?= $agendStatusLabels[$a['status']] ?? $a['status'] ?>
                            </span>
                        </td>
                        <td>R$ <?= number_format($a['valor'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>