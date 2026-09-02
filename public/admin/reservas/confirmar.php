<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

$id = (int)($_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    if (ReservaService::confirmar($id)) {
        flashSet('sucesso', 'Reserva confirmada com sucesso.');
    }
}

header('Location: /admin/reservas/visualizar.php?id=' . $id);
exit;
