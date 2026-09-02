<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloPagina) ? e($tituloPagina) . ' - ' . e(APP_NOME) : e(APP_NOME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-carlores navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/index.php"><?= e(APP_NOME) ?></a>
        <div>
            <a href="/auth/login.php" class="btn btn-outline-carlores btn-sm me-2">Entrar</a>
            <a href="/auth/cadastro.php" class="btn btn-carlores btn-sm">Cadastre-se</a>
        </div>
    </div>
</nav>
