<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirCliente();

$id = (int)($_GET['id'] ?? 0);
$reserva = Reserva::buscarPorId($id);

// Impede que um cliente veja a reserva de outro cliente alterando o id na URL (IDOR)
if (!$reserva || (int)$reserva['cliente_id'] !== (int)$_SESSION['cliente_id']) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$pagamentos = Pagamento::listarPorReserva($id);
$saldoDevedor = PagamentoService::saldoDevedor($reserva);
$statusPagamento = PagamentoService::statusPagamento($reserva);
$itensPacote = PacoteItem::listarPorPacote((int)$reserva['pacote_id']);

$paginaAtual = 'reservas';
$tituloPagina = 'Reserva #' . $reserva['id'];
require ROOT_PATH . '/views/cliente/reservas/detalhe.php';
