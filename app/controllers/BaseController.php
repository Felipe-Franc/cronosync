<?php 
/**
 * BaseController - todos os controllers herdam dele.
 * Centraliza funções comuns: renderizar view, redirecionar, checar auth.
 */
abstract class BaseController
{
    /**
     * Renderiza uma view passando dados.
     * Os dados viram variáveis dentro do arquivo da view via extract().
     */
    protected function view(string $viewName, array $data = []): void
    {
        // Pega cada chave do array $data e cria uma variável com o mesmo nome
        // EX: ['nome' => 'João'] vira $nome = 'João' dentro da view
        extract($data);

        $viewFile = APP_PATH . "/views/{$viewName}.php";

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View não encontrada: {$viewName}");
        }

        require $viewFile;
    }

    /**
     * Renderiza uma view DENTRO do layout administrativo.
     * Captura o conteúdo da view em buffer e injeta no layout como $content.
     */
    protected function layout(string $viewName, array $data = []): void
    {
        extract($data);

        $viewFile = APP_PATH . "/views/{$viewName}.php";
        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View não encontrada: {$viewName}");
        }

        // 1. Caputra o output da view em variável
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // 2. Renderiza o layout (que usa $content e outras variáveis extraídas)
        require APP_PATH . '/views/layouts/admin.php';
    }

    /**
     * Redireciona para outra URL e encerra a execução.
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Verifica se há usuário logado na sessão
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Bloqueia acesso a quem não estiver logado.
     * Use isto no inicio de qualqer ação que precise de autenticação
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
} 
