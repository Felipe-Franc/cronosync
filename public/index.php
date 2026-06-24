<?php 
/**
 * Front controller - unico ponto de entrada da aplicação
 * 1. Careega configurações
 * 2. Define autoload de classes
 * 3. Inicia sessão PHP
 * 4. Confihura rotas
 * 5. Despacha a requisição
 */

require_once __DIR__ . '/../app/config/config.php';

/**
 * Autoload simples - quando você usa "new User()" ou "new Router()",
 * o PHP procura a classe automaticamente nestas pastas.
 * Evita ter que ficar fazendo require_once em todo lugar.
 */
spl_autoload_register(function (string $class): void {
    $paths = [
        APP_PATH . '/core/'         . $class . '.php',
        APP_PATH . '/controllers/'  . $class . '.php',
        APP_PATH . '/models/'       . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Inicia a sessão PHP (Precisa vir antes de qualquer output)
session_start();

// Intancia o router
$router = new Router();

//=============================================
// ROTAS PÚPLICAS (acessíveis sem login)
//=============================================
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

//=============================================
// ROTAS PROTEGIDAS (Exigem login - o controller verifica)
//=============================================
$router->get('/',           [DashboardController::class, 'index']);
$router->get('/dashboard',  [DashboardController::class, 'index']);

// ============================================
// CLIENTES — CRUD completo
// ============================================
$router->get('/clientes',                  [ClientesController::class, 'index']);
$router->get('/clientes/novo',             [ClientesController::class, 'novo']);
$router->post('/clientes',                 [ClientesController::class, 'create']);
$router->get('/clientes/{id}',             [ClientesController::class, 'show']);
$router->get('/clientes/{id}/editar',      [ClientesController::class, 'editar']);
$router->post('/clientes/{id}',            [ClientesController::class, 'update']);
$router->post('/clientes/{id}/excluir',    [ClientesController::class, 'excluir']);

// Despacha a requisição atual
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);