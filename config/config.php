<?php

declare(strict_types=1);

define('APP_NOME', "Carlore's");
define('APP_TIMEZONE', 'America/Sao_Paulo');
define('UPLOAD_MAX_BYTES', 2 * 1024 * 1024); // 2MB
define('UPLOAD_DIR_ABSOLUTO', ROOT_PATH . '/public/assets/uploads/saloes/');
define('UPLOAD_DIR_PUBLICO', '/assets/uploads/saloes/');

date_default_timezone_set(APP_TIMEZONE);

// Nunca exibir erros/stack traces ao usuario final (requisito de seguranca).
// Os erros vao para o log padrao do PHP/Apache - consulte-o em caso de problema.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
