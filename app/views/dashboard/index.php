<?php 
/**
 * Conteúdo da pagina Dashboard.
 * Renderizando dentro de layouts/
 * 
 * @var string $nome Nome do usuário logado
 * @var string $tipo Tipo do usuário (admin/operador)
 */
?>

<div class="welcome-card ">
    <h2>Olá, <span class="saudacao"><?= htmlspecialchars($nome) ?></span>! 👋</h2>
    <p>Você está autenticado no painel.</p>
    <p>O dashboard completo será contruido na Sessão 9.</p>
    <span class="badge">Tipo: <?= htmlspecialchars($tipo) ?></span>
</div>
