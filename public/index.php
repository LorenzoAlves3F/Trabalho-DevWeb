<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

if (estaLogado()) {
    header('Location: ' . (ehAdmin() ? '/admin/index.php' : '/cliente/index.php'));
    exit;
}

require ROOT_PATH . '/views/partials/header_publico.php';
?>
<section class="hero-carlores text-center">
    <div class="container">
        <h1 class="display-4 fw-bold brand-font">Carlore's</h1>
        <p class="lead">Salões elegantes para transformar sua festa em um momento inesquecível.</p>
        <a href="/auth/cadastro.php" class="btn btn-dourado btn-lg mt-3">Solicite sua reserva</a>
    </div>
</section>
<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <h3 class="h5 brand-font">Salões para todos os estilos</h3>
            <p class="text-muted">De eventos intimistas a grandes celebrações, temos o espaço ideal para você.</p>
        </div>
        <div class="col-md-4">
            <h3 class="h5 brand-font">Pacotes completos</h3>
            <p class="text-muted">Decoração, buffet, som e cerimonial em pacotes fechados prontos para o seu evento.</p>
        </div>
        <div class="col-md-4">
            <h3 class="h5 brand-font">Acompanhamento online</h3>
            <p class="text-muted">Acompanhe sua reserva e seus pagamentos direto pelo portal do cliente.</p>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_publico.php'; ?>
