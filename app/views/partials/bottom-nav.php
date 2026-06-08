<?php
/**
 * Bottom Navigation — partial do layout admin (versão mobile).
 *
 * @var array  $menuItems  Lista de itens do menu (filtrada por mobile=true)
 * @var string $activeMenu Chave do item ativo
 */
?>
<nav class="bottom-nav">
    <?php 
    // Filtra só os itens marcados para aparecer no menu mobile
    $mobileItems = array_filter($menuItems, fn($i) => $i['mobile']);
    foreach ($mobileItems as $item):
    ?>
        <a href="<?= $item['url'] ?>"
        class="bn-item <?= ($activeMenu ?? '') === $item['key'] ? 'is-active' : '' ?>">
            <i data-lucide="<?= $item['icon'] ?>"></i>
            <span><?= htmlspecialchars($item['label']) ?></span>
        </a>
    <?php endforeach?>
</nav>