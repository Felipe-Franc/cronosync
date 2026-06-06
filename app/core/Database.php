<?php 
/**
 * Classe Database - Conexão única (singleton simples) usando PDO.
 * PDO = prepared statements é o jeito seguro de falar com o MySQL.
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Retorna a conexão PDO. Cria na primeira chamada e reusa nas seguintes.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf (
                'mysql:host=%s;port=%s;dbname=%s;charset=%s' ,
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // lança exceção em erro
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,            // retorna array associativo
                PDO::ATTR_EMULATE_PREPARES   => false,                      // prepared statements nativos
            ];
            
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Em dev mostra o erro; em prod só log
                if (APP_ENV === 'dev') {
                    die('Erro de conexão: ' . $e->getMessage());
                }
                die('Não foi possível conectar ao banco.');
            }
        }

        return self::$instance;
    }

    // Impede instanciar com `new` - força uso de getConnection()
    private function __construct() {}
    private function __clone() {}
}