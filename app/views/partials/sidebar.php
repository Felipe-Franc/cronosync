<?php
/**
 * Sidebar — partial do layout admin.
 * Variáveis recebidas do layout pai (layouts/admin.php):
 *
 * @var array  $menuItems  Lista de itens do menu (key, label, icon, url, mobile)
 * @var string $activeMenu Chave do item ativo
 */
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h1 class="logo">
            CronoSync
            <span class="logo-accent">- Concierge</span>
        </h1>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menuItems as $item): ?>
            <a href="<?= $item['url'] ?>"
                class="nav-item <?= ($activeMenu ?? '') === $item['key'] ? 'is-active' : '' ?>">
                <i data-lucide="<?= $item['icon'] ?>"></i>
                <span><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                <?= mb_strtoupper(mb_substr($_SESSION['user_nome'] ?? '?'. 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?></div>
                <div class="user-status">
                    <span class="status-dot"></span>
                    Online
                </div>
            </div>
            <a href="/logout" class="user-logout" title="Sair">
                <i data-lucide="log-out"></i>
            </a>
        </div>
    </div>
</aside>

<div class="sidebar-overlay"></div>