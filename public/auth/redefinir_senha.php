<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

$token = (string)($_GET['token'] ?? $_POST['token'] ?? '');
$erros = [];
$tokenValido = $token !== '' && AuthService::validarToken($token) !== null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValido) {
    csrfVerify();

    $erros = Validator::validate($_POST, [
        'senha'             => 'required|senha_forte',
        'senha_confirmacao' => 'required|confirmado',
    ]);
    if (isset($erros['senha_confirmacao']) && !isset($erros['senha'])) {
        $erros['senha'] = 'As senhas não conferem.';
    }

    if (empty($erros)) {
        // Revalida o token do zero (nao confia em estado anterior) antes de gravar a nova senha
        if (AuthService::redefinirSenha($token, (string)$_POST['senha'])) {
            flashSet('sucesso', 'Senha redefinida com sucesso! Faça login com a nova senha.');
            header('Location: /auth/login.php');
            exit;
        }
        $tokenValido = false;
    }
}

$tituloPagina = 'Redefinir senha';
require ROOT_PATH . '/views/partials/header_auth.php';
?>
<h2 class="h4 text-center mb-4 brand-font">Redefinir senha</h2>

<?php if (!$tokenValido): ?>
    <div class="alert alert-danger">Este link é inválido ou já expirou.</div>
    <div class="text-center">
        <a href="/auth/esqueci_senha.php" class="btn btn-carlores">Solicitar novo link</a>
    </div>
<?php else: ?>
    <form method="post" class="needs-validation" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <div class="mb-3">
            <label for="senha" class="form-label">Nova senha</label>
            <input type="password" class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>"
                   id="senha" name="senha" minlength="8" required>
            <div class="form-text">Mínimo 8 caracteres, com maiúsculas, minúsculas e números.</div>
            <div class="invalid-feedback"><?= e($erros['senha'] ?? 'Senha inválida.') ?></div>
        </div>
        <div class="mb-3">
            <label for="senha_confirmacao" class="form-label">Confirmar nova senha</label>
            <input type="password" class="form-control" id="senha_confirmacao" name="senha_confirmacao" minlength="8" required>
            <div class="invalid-feedback">As senhas não conferem.</div>
        </div>
        <button type="submit" class="btn btn-carlores w-100">Redefinir senha</button>
    </form>
<?php endif; ?>
<?php require ROOT_PATH . '/views/partials/footer_auth.php'; ?>
