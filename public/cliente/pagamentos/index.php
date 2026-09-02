<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirCliente();

$reservas = Reserva::listarPorCliente((int)$_SESSION['cliente_id']);

$pagamentos = [];
foreach ($reservas as $r) {
    foreach (Pagamento::listarPorReserva((int)$r['id']) as $p) {
        $p['reserva_data_evento'] = $r['data_evento'];
        $p['salao_nome'] = $r['salao_nome'];
        $pagamentos[] = $p;
    }
}
usort($pagamentos, fn($a, $b) => strcmp($b['data_pagamento'], $a['data_pagamento']));

$paginaAtual = 'pagamentos';
$tituloPagina = 'Meus Pagamentos';
require ROOT_PATH . '/views/cliente/pagamentos/listar.php';
