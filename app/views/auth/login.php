<?php 
/**
 * @var string|null $error Mensagem de erro ao exibir (ou null)
 * @var string $email_preenchido E-mail digitado na tentativa anterior
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <style>
        :root {
            --azul-marinho: #0E2F5A;
            --azul-medio:   #1E9AA8;
            --azul-claro:   #7FAEDB;
            --off-white:    #F7F3EC;
            --dourado:      #E3B766;
            --cinza-quente: #D9D2C7;
            --texto:        #333333;
            --erro-bg:      #FDECEA;
            --erro-borda:   #F5C6CB;
            --errp-cor:     #C0392B;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", roboto, sans-serif;
            background: var(--off-white);
            color: var(--azul-marinho);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(14, 47, 90, 0.12);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand h1 {
            font-size: 1.6rem;
            margin: 0 0 0.4rem 0;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .brand .accent { color: var(--dourado);}

        .brand p {
            margin: 0;
            color: var(--azul-medio);
            font-size: 0.875rem;
        }

        .alert {
            background: var(--erro-bg);
            color: var(--errp-cor);
            border: 1px solid var(--erro-borda);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--cinza-quente);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--azul-marinho);
            background: var(--off-white);
            transition: border-color 0.2s, backgroud 0.2;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--dourado);
            background: #ffffff;
        }

        .btn-entrar {
            width: 100%;
            padding: 0.85rem;
            background: var(--azul-marinho);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: backgroud 0.2s, transform 0.05s;
            margin-top: 0.5rem;
        }

        .btn-entrar:hover { background: var(--azul-medio); }
        .btn-entrar:active { transform: translateY(1px); }

        .link-recuperar {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            color: var(--azul-medio);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .link-recuperar:hover { text-decoration: underline;}
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <h1>CronoSync <span class="accent">-Concierge</span></h1>
            <p>Acesse sua agenda</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login" autocomplete="on">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                type="email"
                id="email"
                name="email"
                required
                autofocus
                value="<?= htmlspecialchars($email_preenchido ?? '') ?>"
                placeholder="seu@email.com"
                >
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input 
                    type="password"
                    id="senha"
                    name="senha"
                    required
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn-entrar">Entrar</button>

            <a href="#" class="link-recuperar">Esqueci minha senha</a>
        </form>
    </div>
</body>
</html>