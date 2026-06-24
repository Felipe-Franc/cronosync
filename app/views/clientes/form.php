<?php
/**
 * Formulário de cliente — usado para CRIAR e EDITAR.
 *
 * @var array  $cliente   Dados do cliente (vazio se novo)
 * @var array  $erros     Erros de validação por campo
 * @var string $modo      'novo' ou 'editar'
 * @var string $action    URL de submit
 */

// Helper local para checar erros e adicionar classe
$temErro = fn(string $campo) => isset($erros[$campo]) ? 'has-error' : '';
$valorOu = fn(string $campo, string $default = '') =>
    htmlspecialchars($cliente[$campo] ?? $default);

$statusOpcoes = [
    'novo'        => 'Novo',
    'em_contato'  => 'Em contato',
    'agendado'    => 'Agendado',
    'atendido'    => 'Atendido',
    'perdido'     => 'Perdido',
    'inativo'     => 'Inativo',
];
$statusAtual = $cliente['status'] ?? 'novo';
?>

<div class="page-header">
    <div>
        <h1><?= $modo === 'novo' ? 'Novo cliente' : 'Editar cliente' ?></h1>
        <div class="subtitulo">
            <a href="/clientes" style="color:inherit;">← Voltar para a lista</a>
        </div>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= htmlspecialchars($action) ?>">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nome">
                    Nome <span class="obrigatorio">*</span>
                </label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?= $valorOu('nome') ?>"
                    class="<?= $temErro('nome') ?>"
                    required
                    maxlength="150"
                    autofocus
                >
                <?php if (isset($erros['nome'])): ?>
                    <div class="error-msg"><?= htmlspecialchars($erros['nome']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= $valorOu('email') ?>"
                    class="<?= $temErro('email') ?>"
                >
                <?php if (isset($erros['email'])): ?>
                    <div class="error-msg"><?= htmlspecialchars($erros['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input
                    type="tel"
                    id="telefone"
                    name="telefone"
                    value="<?= $valorOu('telefone') ?>"
                    placeholder="(11) 9 9999-9999"
                >
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp</label>
                <input
                    type="tel"
                    id="whatsapp"
                    name="whatsapp"
                    value="<?= $valorOu('whatsapp') ?>"
                    placeholder="(11) 9 9999-9999"
                >
            </div>

            <div class="form-group">
                <label for="origem">Origem do lead</label>
                <input
                    type="text"
                    id="origem"
                    name="origem"
                    value="<?= $valorOu('origem') ?>"
                    placeholder="Indicação, Instagram, Site..."
                    maxlength="80"
                >
            </div>

            <div class="form-group full-width">
                <label for="status">Status</label>
                <select id="status" name="status" class="<?= $temErro('status') ?>">
                    <?php foreach ($statusOpcoes as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $statusAtual === $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($erros['status'])): ?>
                    <div class="error-msg"><?= htmlspecialchars($erros['status']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group full-width">
                <label for="observacoes">Observações</label>
                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                ><?= $valorOu('observacoes') ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="check"></i>
                <?= $modo === 'novo' ? 'Cadastrar cliente' : 'Salvar alterações' ?>
            </button>
            <a href="/clientes" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>