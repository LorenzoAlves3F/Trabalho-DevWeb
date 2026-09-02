<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $id = (int)($_POST['id'] ?? 0);
    $pagamento = Pagamento::buscarPorId($id);

    if ($pagamento) {
        $reservaId = (int)$pagamento['reserva_id'];
        Pagamento::excluir($id);
        flashSet('sucesso', 'Pagamento excluído.');
        header('Location: /admin/reservas/visualizar.php?id=' . $reservaId);
        exit;
    }
}

header('Location: /admin/pagamentos/index.php');
exit;
