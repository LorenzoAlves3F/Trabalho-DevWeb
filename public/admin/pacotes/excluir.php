<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $id = (int)($_POST['id'] ?? 0);
    $pacote = Pacote::buscarPorId($id);

    if ($pacote) {
        Pacote::definirAtivo($id, !$pacote['ativo']);
        flashSet('sucesso', $pacote['ativo'] ? 'Pacote desativado.' : 'Pacote reativado.');
    }
}

header('Location: /admin/pacotes/index.php');
exit;
