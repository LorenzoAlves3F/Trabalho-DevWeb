<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

encerrarSessao();

header('Location: /index.php');
exit;
