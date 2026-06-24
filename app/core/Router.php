<?php 
/**
 * Router — mapeia URL + método HTTP para Controller@método.
 * Suporta parâmetros dinâmicos via {nome}.
 *
 * Exemplos:
 *   $router->get('/clientes/{id}', [ClientesController::class, 'show']);
 *   → ClientesController::show($id) recebe o id automaticamente
 */
class Router
{
    private array $routes = [
        'GET'   => [],
        'POST'  => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Converte uma rota como '/clientes/{id}' em uma regex que extrai os params.
     * '/clientes/{id}' vira '#^/clientes/(?P<id>[^/]+)$#'
     */
    private function addRoute(string $method, string $path, array $handler): void
    {
        // Substitui {nome} por um grupo de captura nomeado
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        //Remove query string (?param=valor) e barra final
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if ($path === '') $path = '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                // Filtra só os params nomeados (descarta os indices numéricos)
                $params = array_filter(
                    $matches,
                    fn($k) => !is_numeric($k),
                    ARRAY_FILTER_USE_KEY
                );

                [$class, $action] = $route['handler'];
                $controller = new $class();
                $controller->$action(...array_values($params));
                return;
            }
        }
        
        // Nenhuma rota deu match
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        echo "<p>A URL <code>" . htmlspecialchars($path) . "</code> não está mapeada.</p>";
    }
}