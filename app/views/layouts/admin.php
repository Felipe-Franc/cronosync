<?php
/**
 * Layout adminitrativo - usando por todas as telas autenticads.
 * 
 * Variáveis esperadas (vindas do controller via layout()):
 * @var string $pageTitle  Título da página exibido na topbar
 * @var string $activeMenu Chave do item ativo (ex: 'dashboard')
 * @var string $content    HTML do conteúdo (preenchido pelo BaseController)
 */

// =====================================================
// Fonte única de verdade para o menu — usada na sidebar
// E no bottom nav. Adicionar/remover item = editar aqui só.
// =====================================================
$menuItems = [
    ['key' => 'dashboard',      'label' => 'Dashboard',     'icon' => 'layout-dashboard',   'url' => '/dashboard',      'mobile' => false],
    ['key' => 'agenda',         'label' => 'Agenda',        'icon' => 'calendar',           'url' => '/agenda',         'mobile' => true],
    ['key' => 'leads',          'label' => 'Leads',         'icon' => 'user-plus',          'url' => '/leads',          'mobile' => true],
    ['key' => 'clientes',       'label' => 'Clientes',      'icon' => 'users',              'url' => '/clientes',       'mobile' => true],
    ['key' => 'servicos',       'label' => 'Serviços',      'icon' => 'briefcase',          'url' => '/servicos',       'mobile' => false],
    ['key' => 'faturamento',    'label' => 'Faturamento',   'icon' => 'wallet',             'url' => '/faturamento',    'mobile' => true],
    ['key' => 'relatorios',     'label' => 'Relatórios',    'icon' => 'bar-chart-3',        'url' => '/relatorio',      'mobile' => false],
    ['key' => 'configuracoes',  'label' => 'Configurações', 'icon' => 'settings',           'url' => '/configuracoes',  'mobile' => false],
];
?>
<!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($pageTitle ?? 'CronoSync') ?> - <?= APP_NAME ?></title>
        <link rel="stylesheet" href="/assets/css/admin.css">
    </head>
    <body>
        <body class="admin-layout">
            
            <?php require APP_PATH . '/views/partials/sidebar.php'; ?>

            <div class="main">
                <?php require APP_PATH . '/views/partials/topbar.php'; ?>

                <main class="content">
                    <?= $content ?>
                </main>
            </div>

            <?php require APP_PATH . '/views/partials/bottom-nav.php'; ?>

            <!-- Ícone Lucide via CDN -->
             <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
             <script>
                 // Aguarda Lucide carregar antes de chamar creatIcons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            </script>
             <script src="/assets/js/admin.js"></script>
        </body>
</body>
</html>