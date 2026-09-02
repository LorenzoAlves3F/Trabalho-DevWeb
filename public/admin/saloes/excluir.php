<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $id = (int)($_POST['id'] ?? 0);
    $salao = Salao::buscarPorId($id);

    if ($salao) {
        Salao::definirAtivo($id, !$salao['ativo']);
        flashSet('sucesso', $salao['ativo'] ? 'Salão desativado.' : 'Salão reativado.');
    }
}

header('Location: /admin/saloes/index.php');
exit;
