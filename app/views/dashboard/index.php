<?php 
/**
 * Variáveis recebidas via extract() no BaseController::view()
 * @var string $nome Nome do usuário logado
 * @var string $tipo Tipo do usuário (admin/operador)
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= APP_NAME ?></title>
    <style>
        :root {
            --azul-marinho: #0E2F5A;
            --azul-medio:   #1E5AA8;
            --off-white:    #F7F3EC;
            --dourado:      #E3B766;
            --cinza-quente: #D9D2C7;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: var(--off-white);
            color: var(--azul-marinho);
            min-height: 100vh;
        }

        .topbar {
            background: var(--azul-marinho);
            color: #ffffff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .topbar a {
            color: #ffffff;
            text-decoration:none;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: background 0.2s;
        }

        .topbar a:hover { background: rgba(255, 255, 255, 0.2); }

        .container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .welcome-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(14, 47, 90, 0.08);
            padding: 2.5rem;
            text-align: center;
        }

        .welcome-card h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }

        .welcome-card .saudacao { color: var(--dourado);}

        .welcome-card p {
            color: var(--azul-medio);
            margin: 0.5rem 0;
        }

        .badge {
            display: inline-block;
            background: var(--off-white);
            color: var(--azul-marinho);
            padding:  0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
            border: 1px solid var(--cinza-quente);
        }
    </style>
</head>
<body>
    <header class="topbar">
        <h1>CronoSync <span class="accent">- Concierge</span></h1>
        <a href="/logout">Sair</a>
    </header>

    <div class="container">
        <div class="welcome-card">
             <h2>Olá, <span class="saudacao"><?= htmlspecialchars($nome) ?></span>! 👋</h2>
             <p>Você está autenticado no painel.</p>
             <p>O dashboard completo será contruido na Sessão 9.</p>
             <span class="badge">Tipo: <?= htmlspecialchars($tipo) ?></span>
        </div>
    </div>
</body>
</html>