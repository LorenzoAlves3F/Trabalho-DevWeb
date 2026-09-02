<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$pagamento = Pagamento::buscarPorId($id);

if (!$pagamento) {
    http_response_code(404);
    require ROOT_PATH . '/views/erros/404.php';
    exit;
}

$reserva = Reserva::buscarPorId((int)$pagamento['reserva_id']);
$dados = $pagamento;
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $resultado = PagamentoService::atualizar($reserva, $id, $_POST);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Pagamento atualizado com sucesso.');
        header('Location: /admin/reservas/visualizar.php?id=' . $reserva['id']);
        exit;
    }
    $erros = $resultado['erros'];
}

$saldoDevedor = PagamentoService::saldoDevedor($reserva);

$modoEdicao = true;
$paginaAtual = 'reservas';
$tituloPagina = 'Editar Pagamento';
require ROOT_PATH . '/views/admin/pagamentos/form.php';
