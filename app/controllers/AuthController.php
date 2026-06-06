<?php 
/**
 * AuthController - controla login, logout e fluxo de autenticação
 */
class AuthController extends BaseController
{
    /**
     * GET /login - mostra a tela de login.
     */
    public function showLogin(): void
    {
        // Se já está logado, manda direto pro dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }

        //Pega mensagem de erro guardada na sessão (se houver)
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']); // mostra uma vez só

        $this->view('auth/login', [
            'error' => $error,
            'email_preenchido' => $_SESSION['flash_email'] ?? '',
        ]);
        unset($_SESSION['flash_email']);
    }
    
    /**
     * POST /login - recebe os dados do formulário e tenta autenticar.
     */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        //validação básica
        if ($email === '' || $senha === '') {
            $_SESSION['flash_error'] = 'Informe e-mail e senha.';
            $_SESSION['flash_email'] = $email;
            $this->redirect('/login');
            return;
        }

        // Busca o usuário no banco via model
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Verifica se existe E se a senha bate com o hash armazenado
        if (!$user || !password_verify($senha, $user['senha'])) {
            $_SESSION['flash_error'] = 'E-mail ou senha inválidos.';
            $_SESSION['flash_email'] = $email;
            $this->redirect('/login');
            return;
        }

        // Login OK - regenera o ID da sessão (proteção contra session fixation)
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo_usuario'];

        $this->redirect('/dashboard');
    }

    /**
     * GET /logout - destrói a sessão e volta pro login.
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }
}