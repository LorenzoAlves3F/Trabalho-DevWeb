<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$saloes = Salao::listarTodos();

$paginaAtual = 'saloes';
$tituloPagina = 'Salões';
require ROOT_PATH . '/views/admin/saloes/listar.php';
