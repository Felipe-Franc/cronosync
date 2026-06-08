<?php
/**
 * Topbar — partial do layout admin.
 *
 * @var string $pageTitle Título da página atual
 */
?>
<header class="topbar">
    <button class="menu-toggle" type="button" aria-label="Abrir menu">
        <i data-lucide="menu"></i>
    </button>

    <h2 class="page-title">
        <?= htmlspecialchars($pageTitle ?? 'CronoSync') ?>
    </h2>

    <div class="topbar-actions">
        <button class="action-btn" type="button" aria-label="Notificações">
            <i data-lucide="bell"></i>
        </button>
    </div>
</header>