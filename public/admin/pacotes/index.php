<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$pacotes = Pacote::listarTodos();
$itensPorPacote = [];
foreach ($pacotes as $pacote) {
    $itensPorPacote[$pacote['id']] = PacoteItem::listarPorPacote((int)$pacote['id']);
}

$paginaAtual = 'pacotes';
$tituloPagina = 'Pacotes';
require ROOT_PATH . '/views/admin/pacotes/listar.php';
