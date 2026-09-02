<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$pagamentos = Pagamento::listarTodosComReserva();

$paginaAtual = 'pagamentos';
$tituloPagina = 'Pagamentos';
require ROOT_PATH . '/views/admin/pagamentos/listar.php';
