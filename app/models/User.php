<?php 
/**
 * Model User - encapsula consultas à tabela `users`.
 * O Controller NUNCA monta SQL direto; ele pede ao Model.
 */
class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    /**
     * Busca um usuário ativo pelo e-mail.
     * Retorna o array com os dados, ou null se não encontrar.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, nome, email, senha, tipo_usuario
                FROM users
                WHERE email = ? AND status = 'ativo'
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}