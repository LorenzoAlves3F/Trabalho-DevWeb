<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

$erros = [];
$email = '';
$linkSimulado = null;
$mensagemGenerica = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $email = trim((string)($_POST['email'] ?? ''));

    $erros = Validator::validate($_POST, ['email' => 'required|email']);

    if (empty($erros)) {
        $token = AuthService::solicitarResetSenha($email);
        // Mensagem sempre generica - nao revela se o e-mail existe ou nao (evita enumeracao de usuarios)
        $mensagemGenerica = 'Se este e-mail estiver cadastrado, um link de redefinição foi gerado abaixo.';

        if ($token) {
            $linkSimulado = '/auth/redefinir_senha.php?token=' . urlencode($token);
        }
    }
}

$tituloPagina = 'Recuperar senha';
require ROOT_PATH . '/views/partials/header_auth.php';
?>
<h2 class="h4 text-center mb-4 brand-font">Esqueci minha senha</h2>

<?php if ($mensagemGenerica): ?>
    <div class="alert alert-info"><?= e($mensagemGenerica) ?></div>
    <?php if ($linkSimulado): ?>
        <div class="alert alert-warning">
            <strong>Ambiente de demonstração:</strong> como este projeto não envia e-mails reais, o link de
            redefinição está disponível diretamente abaixo (em produção ele chegaria por e-mail).
            <br>
            <a href="<?= e($linkSimulado) ?>"><?= e($linkSimulado) ?></a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <?= csrfField() ?>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail cadastrado</label>
        <input type="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
               id="email" name="email" value="<?= e($email) ?>" required>
        <div class="invalid-feedback"><?= e($erros['email'] ?? 'Informe um e-mail válido.') ?></div>
    </div>
    <button type="submit" class="btn btn-carlores w-100">Gerar link de redefinição</button>
</form>
<div class="text-center mt-3 small">
    <a href="/auth/login.php">Voltar para o login</a>
</div>
<?php require ROOT_PATH . '/views/partials/footer_auth.php'; ?>
