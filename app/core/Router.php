<?php 
/**
 * Router - mapeia URL + método HTTP para um Controller@método
 * 
 * Ex :
 * $router->get('/login', [AuthController::class, 'showLogin']);
 * $router->post('/login', [AuthController::class, 'login']);
 * $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'])
 */
class Router
{
    private array $routes = [
        'GET'   => [],
        'POST'  => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Dado um método HTTP e URI. decide quem executa.
     * Se nenhuma roata base, retorna 404.
     */
    public function dispatch(string $method, string $uri): void
    {
        //Remove query string (?param=valor) e barra final
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "<h1>404 - Página não encontrada</h1>";
            echo "<p>A URL <code>{$path}</code> não está mapeada.</p>";
            return;
        }

        [$controllerClass, $action] = $this->routes[$method][$path];
        $controller = new $controllerClass();
        $controller->$action();
    }
}