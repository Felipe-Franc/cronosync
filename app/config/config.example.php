<?php
/**
 * CronoSync - concierge
 * 
 * EXEMPLO de configuração
 */

// Ambiente: 'dev' mostra erros; 'prod' esconde.
define('APP_ENV', 'dev');
define('APP_NAME', 'CronoSync');
define('APP_URL', 'http://cronosync.test');

// Banco de dados — preencha com suas credenciais locais
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'cronosync_db');
define('DB_USER', 'SEU_USUARIO_AQUI');
define('DB_PASS', 'SUA_SENHA_AQUI');
define('DB_CHARSET', 'utf8mb4');

// Caminhos absolutos
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH',  ROOT_PATH . '/app');

// Exibição de erros conforme ambiente
if (APP_ENV === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}