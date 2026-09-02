<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

if (estaLogado()) {
    header('Location: ' . (ehAdmin() ? '/admin/index.php' : '/cliente/index.php'));
    exit;
}

$erros = [];
$dados = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados['email'] = trim((string)($_POST['email'] ?? ''));
    $senha = (string)($_POST['senha'] ?? '');

    $erros = Validator::validate($_POST, [
        'email' => 'required|email',
        'senha' => 'required',
    ]);

    if (empty($erros)) {
        $resultado = AuthService::login($dados['email'], $senha);
        if ($resultado) {
            iniciarSessaoUsuario($resultado['usuario'], $resultado['cliente_id']);
            header('Location: ' . ($resultado['usuario']['tipo'] === 'admin' ? '/admin/index.php' : '/cliente/index.php'));
            exit;
        }
        $erros['geral'] = 'E-mail ou senha inválidos.';
    }
}

$tituloPagina = 'Entrar';
require ROOT_PATH . '/views/partials/header_auth.php';
?>
<h2 class="h4 text-center mb-4 brand-font">Acesse sua conta</h2>

<?php if (!empty($erros['geral'])): ?>
    <div class="alert alert-danger"><?= e($erros['geral']) ?></div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <?= csrfField() ?>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
               id="email" name="email" value="<?= e($dados['email']) ?>" required>
        <div class="invalid-feedback"><?= e($erros['email'] ?? 'Informe um e-mail válido.') ?></div>
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>"
               id="senha" name="senha" required>
        <div class="invalid-feedback"><?= e($erros['senha'] ?? 'Informe sua senha.') ?></div>
    </div>
    <button type="submit" class="btn btn-carlores w-100">Entrar</button>
</form>
<div class="d-flex justify-content-between mt-3 small">
    <a href="/auth/esqueci_senha.php">Esqueci minha senha</a>
    <a href="/auth/cadastro.php">Criar conta de cliente</a>
</div>
<?php require ROOT_PATH . '/views/partials/footer_auth.php'; ?>
