<?php
/**
 * ClientesController - CRUD completo de Clientes/Leads.
 * 7 actions: index, show, novo, create, editar, update, excluir.
 */
class ClientesController extends BaseController
{
    private Cliente $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }

    /**
     * Get /clientes - lista com busca, filtros e paginação.
     */
    public function index(): void
    {
        $this->requireAuth();

        $busca = trim($_GET['busca']    ?? '');
        $status = trim($_GET['status']  ?? '');
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $porPagina = 15;

        $clientes = $this->clienteModel->all($busca, $status, $pagina, $porPagina);
        $total = $this->clienteModel->total($busca, $status);
        $totalPaginas = (int) ceil($total / $porPagina);

        // Mensagem de sucesso vinda do create/update/excluir
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $this->layout('clientes/index', [
            'pageTitle'     => 'Clientes',
            'activeMenu'    => 'clientes',
            'clientes'      => $clientes,
            'busca'         => $busca,
            'status'        => $status,
            'pagina'        => $pagina,
            'totalPaginas'  => $totalPaginas,
            'total'         => $total,
            'flashSuccess'  => $flashSuccess,
        ]);
    }

    /**
     * Get /clientes/{id} - detalhes do cliente + histórico.
     */
    public function show(string $id): void
    {
        $this->requireAuth();

        $cliente = $this->clienteModel->find((int) $id);
        if (!$cliente) {
            $this->redirect('/clientes');
            return;
        }

        $historico = $this->clienteModel->historico((int) $id);

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $this->layout('clientes/show', [
            'pageTitle'     => $cliente['nome'],
            'activeMenu'    => 'clientes',
            'cliente'       => $cliente,
            'historico'     => $historico,
            'flashSuccess'  => $flashSuccess,
        ]);
    }

    /**
     * GET /clientes/novo - formulário de cadastro.
     */
    public function novo(): void
    {
        $this->requireAuth();

        //recupera dados/erros do flash (se voltou de erro de validação)
        $cliente = $_SESSION['flash_dados'] ?? [];
        $erros   = $_SESSION['flash_erros'] ?? [];
        unset($_SESSION['flash_dados'], $_SESSION['flash_erros']);

        $this->layout('clientes/form', [
            'pageTitle'     => 'Novo cliente',
            'activeMenu'    => 'clientes',
            'cliente'       => $cliente,
            'erros'         => $erros,
            'modo'          => 'novo',
            'action'        => '/clientes',
        ]);
    }

    /**
     * Post /clientes - cria um novo clientes.
     */
    public function create(): void
    {
        $this->requireAuth();

        $dados = $this->extrairDados();
        $erros = $this->validar($dados);

        if ($erros) {
            $_SESSION['flash_erros'] = $erros;
            $_SESSION['flash_dados'] = $dados;
            $this->redirect('/clientes/novo');
            return;
        }

        $id = $this->clienteModel->create($dados);
        $_SESSION['flash_success'] = "Cliente \"{$dados['nome']}\" cadastrado com sucesso.";
        $this->redirect("/clientes/{$id}");
    }

    /**
     * GET /clientes/{id}/editar - formulário de edição.
     */
    public function editar(string $id): void
    {
        $this->requireAuth();

        $cliente = $this->clienteModel->find((int) $id);
        if (!$cliente) {
            $this->redirect('/clientes');
            return;
        }

        // Se voltou de erro de validação, sobrescreve com os dados que o usuário digitou.
        if (isset($_SESSION['flash_dados'])) {
            $cliente = array_merge($cliente, $_SESSION['flash_dados']);
        }
        $erros = $_SESSION['flash_erros'] ?? [];
        unset($_SESSION['flash_dados'], $_SESSION['flash_erros']);

        $this->layout('clientes/form', [
            'pageTitle'     => 'Editar ' . $cliente['nome'],
            'activeMenu'    => 'clientes',
            'cliente'       => $cliente,
            'erros'         => $erros,
            'modo'          => 'editar',
            'action'        => "/clientes/{$id}",  
        ]);
    }

    /**
     * POST /cientes/{id} - atualiza um cliente existente.
     */
    public function update(string $id): void
    {
    /*    // 🐛 DEBUG TEMPORÁRIO — REMOVER DEPOIS
    echo "<pre style='background:#fff;padding:20px;font-family:monospace'>";
    echo "=== POST recebido pelo update() ===\n";
    var_dump($_POST);
    echo "\n=== extrairDados() retorna ===\n";
    var_dump($this->extrairDados());
    die();
    */

        $this->requireAuth();

        $cliente = $this->clienteModel->find((int) $id);
        if (!$cliente) {
            $this->redirect('/clientes');
            return;
        }

        $dados = $this->extrairDados();
        $erros = $this->validar($dados);

        if ($erros) {
            $_SESSION['flash_erros'] = $erros;
            $_SESSION['flash_dados'] = $dados;
            $this->redirect("/clientes/{$id}/editar");
            return;
        }

        $this->clienteModel->update((int) $id, $dados);
        $_SESSION['flash_success'] = "Cliente \"{$dados['nome']}\" atualizado.";
        $this->redirect("/clientes/{$id}");
    }

    /**
     * POST /clientes/{id}/excluir - soft delete.
     */
    public function excluir(string $id): void
    {
        $this->requireAuth();

        $cliente = $this->clienteModel->find((int) $id);
        if ($cliente) {
            $this->clienteModel->softDelete((int) $id);
            $_SESSION['flash_success'] = "Cliente \"{$cliente['nome']}\" marcado como inativo.";
        }
        $this->redirect('/clientes');
    }

    /**
     * Extrai e normaliza os dados do POST.
     * Strings vaias viram null (exceto nome e satus, que são obrigatórios).
     */
    private function extrairDados(): array
    {
        return [
            'nome'          => trim($_POST['nome'] ?? ''),
            'telefone'      => trim($_POST['telefone'] ?? '') ?: null,
            'whatsapp'      => trim($_POST['whatsapp'] ?? '') ?: null,
            'email'         => trim($_POST['email'] ?? '') ?: null,
            'origem'        => trim($_POST['origem'] ?? '') ?: null,
            'status'        => trim($_POST['status'] ?? 'novo'),
            'observacoes'   => trim($_POST['observacoes'] ?? '') ?: null,
        ];
    }

    /**
     * Valida os dados. Retorna array vazio se OK, ou array de erros por campo.
     */
    private function validar(array $dados): array
    {
        $erros = [];

        if  ($dados ['nome'] === '') {
            $erros['nome'] = 'O nome é obrigatório.';
        } elseif (mb_strlen($dados['nome']) > 150)   {
            $erros['nome'] = 'Onome deve ter no maximo 150 caracteres.';
        }

        if ($dados['email'] && !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'E-mail invalido.';
        }

        $statusValidos = ['novo', 'em_contato', 'agendado', 'atendido', 'perdido', 'inativo'];
        if (!in_array($dados['status'], $statusValidos, true)) {
            $erros['status'] = 'Status inválido.';
        }

        return $erros;
    }
}