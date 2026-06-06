<?php
/**
 * DashboardController - tela inicial pós login.
 * Por enquando só uma msg boas-vindas. Vamos preencher na sessão 9.
 */
class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();   // Bloqueia quem não esteja logado

        $this->view('dashboard/index', [
            'nome' => $_SESSION['user_nome'],
            'tipo' => $_SESSION['user_tipo'],
        ]);
    }
}
