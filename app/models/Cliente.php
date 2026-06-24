<?php
/**
 * Model Cliente - encapsula consultas á tabela `clientes`.
 * Inclui busca, paginação, filtro por status e histórico de agendamentos.
 */
class Cliente
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Lista clientes com filtros, busca e paginação.
     */
    public function all(string $busca = '', string $status = '',
                        int $pagina = 1, int $porPagina = 15): array
    {
        [$where, $params] = $this->montarFiltros($busca, $status);

        $sql = "SELECT * FROM clientes";
        if ($where) $sql .= " WHERE " . implode( ' AND ', $where);
        $sql .= " ORDER BY criado_em DESC";

        $offset = ($pagina - 1) * $porPagina;
        $sql .= " LIMIT {$porPagina} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Conta o total de cliente que batem com os filtros -usado para paginação
     */
    public function total(string $busca = '', string $status = ''): int 
    {
        [$where, $params] = $this->montarFiltros($busca, $status);

        $sql = "SELECT COUNT(*) AS total FROM clientes";
        if ($where) $sql .= " WHERE " . implode( ' AND ', $where);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['total'];
    }

    /**
     * busca um cliente pelo ID.
     */
    public function find(int $id): ?array 
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    /**
     * Cria um novo cliente. Retorna o ID gerado.
     */
    public function create(array $dados): int 
    {
        $sql = "INSERT INTO clientes
                (nome, telefone, whatsapp, email, origem, status, observacoes)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $dados['nome'],
            $dados['telefone'] ?? null,
            $dados['whastapp'] ?? null,
            $dados['email'] ?? null,
            $dados['origem'] ?? null,
            $dados['status'] ?? 'novo',
            $dados['observacoes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * atualiza um cliente exitente.
     */
    public function update( int $id, array $dados): bool
    {
        $sql = "UPDATE clientes SET
                    nome = ?, telefone = ?, whatsapp = ?, email = ?,
                    origem = ?, status = ?, observacoes = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['telefone'] ?? null,
            $dados['whastapp'] ?? null,
            $dados['email'] ?? null,
            $dados['origem'] ?? null,
            $dados['status'] ?? 'novo',
            $dados['observacoes'] ?? null,
            $id,
        ]);
    }

    /**
     * Soft delet - apenas marca como inativo, não apaga o registro.
     * Preserva historico de agendamentos e pagamentos.
     */
    public function softDelete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE clientes SET status = 'inativo' WHERE id =?");
        return $stmt->execute([$id]);
    }

    /**
     * Retorna o histórico de agendamentos de um cliente.
     */
    public function historico(int $clienteId): array
    {
        $sql = "SELECT a.id, a.data, a.hora_inicio, a.status, a.valor,
                        s.nome AS servico_nome
                        FROM agendamentos a
                        LEFT JOIN servicos s ON a.servico_id = s.id
                        WHERE a.cliente_id = ?
                        ORDER BY a.data DESC, a.hora_inicio DESC
                        LIMIT 50";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }

    /**
     * Centraliza a montagem dos filtros - usado em All() e total().
     * Evita duplicar lógica.
     */
    private function montarFiltros(string $busca, string $status): array
    {
        $where = [];
        $params = [];

        if ($busca !== '') {
            $where[] = "(nome LIKE ? OR email LIKE ? OR telefone LIKE ?)";
            $params[] = "%{$busca}%";
            $params[] = "%{$busca}%";
            $params[] = "%{$busca}%";
        }

        if ($status !== '') {
            $where[] = "status = ?";
            $params[] = $status;
        }

        return [$where, $params];
    }
}