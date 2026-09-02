<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/bootstrap.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $usuarioId = (int)($_POST['id'] ?? 0);
    $usuario = Usuario::buscarPorId($usuarioId);

    if ($usuario && $usuario['tipo'] === 'cliente') {
        Usuario::definirAtivo($usuarioId, !$usuario['ativo']);
        flashSet('sucesso', $usuario['ativo'] ? 'Cliente desativado.' : 'Cliente reativado.');
    }
}

header('Location: /admin/clientes/index.php');
exit;
