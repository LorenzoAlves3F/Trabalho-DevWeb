<?php $usuario = usuarioLogado(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina ?? 'Minha Conta') ?> - <?= e(APP_NOME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-carlores navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/cliente/index.php"><?= e(APP_NOME) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCliente">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navCliente">
            <ul class="navbar-nav me-auto">
                <?php
                $paginaAtual = $paginaAtual ?? '';
                $itensMenu = [
                    'dashboard'  => ['/cliente/index.php', 'Início'],
                    'reservas'   => ['/cliente/reservas/index.php', 'Minhas Reservas'],
                    'nova'       => ['/cliente/reservas/nova.php', 'Solicitar Reserva'],
                    'pagamentos' => ['/cliente/pagamentos/index.php', 'Meus Pagamentos'],
                    'perfil'     => ['/cliente/perfil/editar.php', 'Meu Perfil'],
                ];
                foreach ($itensMenu as $chave => $item):
                    [$url, $rotulo] = $item;
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $paginaAtual === $chave ? 'active' : '' ?>" href="<?= e($url) ?>"><?= e($rotulo) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item"><span class="nav-link">Olá, <?= e($usuario['nome']) ?></span></li>
                <li class="nav-item"><a class="nav-link" href="/logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container py-4">
    <h1 class="h3 mb-4"><?= e($tituloPagina ?? '') ?></h1>
    <?= flashRender() ?>
