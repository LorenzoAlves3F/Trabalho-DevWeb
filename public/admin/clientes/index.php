<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$clientes = Cliente::listarComUsuario();

$paginaAtual = 'clientes';
$tituloPagina = 'Clientes';
require ROOT_PATH . '/views/admin/clientes/listar.php';
