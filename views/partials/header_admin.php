<?php $usuario = usuarioLogado(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina ?? 'Painel') ?> - <?= e(APP_NOME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
    <link href="/assets/css/print.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-carlores navbar-dark no-print">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin/index.php"><?= e(APP_NOME) ?> <small class="fs-6 fw-normal">admin</small></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navAdmin">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><span class="nav-link">Olá, <?= e($usuario['nome']) ?></span></li>
                <li class="nav-item"><a class="nav-link" href="/logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 sidebar-carlores no-print">
            <ul class="nav flex-column px-2">
                <?php
                $paginaAtual = $paginaAtual ?? '';
                $itensMenu = [
                    'dashboard'   => ['/admin/index.php', 'Dashboard'],
                    'saloes'      => ['/admin/saloes/index.php', 'Salões'],
                    'pacotes'     => ['/admin/pacotes/index.php', 'Pacotes'],
                    'clientes'    => ['/admin/clientes/index.php', 'Clientes'],
                    'reservas'    => ['/admin/reservas/index.php', 'Reservas'],
                    'pagamentos'  => ['/admin/pagamentos/index.php', 'Pagamentos'],
                    'faturamento' => ['/admin/relatorios/faturamento.php', 'Relatório de Faturamento'],
                    'ocupacao'    => ['/admin/relatorios/ocupacao.php', 'Agenda de Ocupação'],
                ];
                foreach ($itensMenu as $chave => $item):
                    [$url, $rotulo] = $item;
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $paginaAtual === $chave ? 'active' : '' ?>" href="<?= e($url) ?>"><?= e($rotulo) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        <main class="col-lg-10 py-4">
            <h1 class="h3 mb-4"><?= e($tituloPagina ?? '') ?></h1>
            <?= flashRender() ?>
