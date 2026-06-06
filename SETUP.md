# Setup — CronoSync

Guia rápido de instalação local.

## Pré-requisitos

- PHP 8.1+ com `pdo_mysql`
- MySQL 8.0+ ou MariaDB 10.4+
- Apache (recomendado: [Laragon](https://laragon.org))

## Passos

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU_USUARIO/cronosync.git
cd cronosync
```

### 2. Criar o banco e importar o schema

```sql
CREATE DATABASE cronosync_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

```bash
mysql -u root cronosync_db < database/schema.sql
```

### 3. Configurar credenciais

```bash
cp app/config/config.example.php app/config/config.php
```

Edite `app/config/config.php` com seus dados do MySQL:

```php
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 4. Configurar virtual host

Aponte o `DocumentRoot` para a pasta `public/`.

No Laragon é automático — coloque o projeto em `C:/laragon/www/cronosync/` e reinicie o Apache.

### 5. Acessar

`http://cronosync.test`

**Credenciais padrão (dev):** `admin@cronosync.local` / `admin123`