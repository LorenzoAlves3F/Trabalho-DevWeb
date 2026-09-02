<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

require_once ROOT_PATH . '/helpers/format_helper.php';
require_once ROOT_PATH . '/helpers/flash_helper.php';
require_once ROOT_PATH . '/helpers/csrf_helper.php';
require_once ROOT_PATH . '/helpers/validation_helper.php';
require_once ROOT_PATH . '/helpers/auth_helper.php';

// Autoload simples: nome da classe == nome do arquivo, procurado em models/ e depois services/
spl_autoload_register(function (string $classe): void {
    foreach (['models', 'services'] as $pasta) {
        $caminho = ROOT_PATH . "/{$pasta}/{$classe}.php";
        if (is_file($caminho)) {
            require_once $caminho;
            return;
        }
    }
});

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_name('carlores_sessao');
session_start();
