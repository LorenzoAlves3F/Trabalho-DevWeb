<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina ?? 'Acesso') ?> - <?= e(APP_NOME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand-font fs-2 fw-bold"><?= e(APP_NOME) ?></div>
            <div class="text-muted small">Gestão de eventos e salões de festa</div>
        </div>
        <?= flashRender() ?>
