<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/bootstrap.php';

if (estaLogado()) {
    header('Location: ' . (ehAdmin() ? '/admin/index.php' : '/cliente/index.php'));
    exit;
}

$dados = ['nome' => '', 'email' => '', 'telefone' => '', 'cpf' => '', 'endereco' => ''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerify();
    $dados = array_merge($dados, $_POST);

    $resultado = AuthService::registrarCliente($_POST);
    if ($resultado['sucesso']) {
        flashSet('sucesso', 'Cadastro realizado com sucesso! Faça login para continuar.');
        header('Location: /auth/login.php');
        exit;
    }
    $erros = $resultado['erros'];
}

$tituloPagina = 'Criar conta';
require ROOT_PATH . '/views/partials/header_auth.php';
?>
<h2 class="h4 text-center mb-4 brand-font">Crie sua conta de cliente</h2>

<?php if (!empty($erros['geral'])): ?>
    <div class="alert alert-danger"><?= e($erros['geral']) ?></div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <?= csrfField() ?>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome completo</label>
        <input type="text" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
               id="nome" name="nome" minlength="3" maxlength="120" value="<?= e($dados['nome']) ?>" required>
        <div class="invalid-feedback"><?= e($erros['nome'] ?? 'Informe seu nome completo.') ?></div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
               id="email" name="email" maxlength="150" value="<?= e($dados['email']) ?>" required>
        <div class="invalid-feedback"><?= e($erros['email'] ?? 'Informe um e-mail válido.') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control <?= isset($erros['cpf']) ? 'is-invalid' : '' ?>"
                   id="cpf" name="cpf" data-mascara="cpf" maxlength="14" placeholder="000.000.000-00"
                   value="<?= e($dados['cpf']) ?>" required>
            <div class="invalid-feedback"><?= e($erros['cpf'] ?? 'Informe um CPF válido.') ?></div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                   id="telefone" name="telefone" data-mascara="telefone" maxlength="15" placeholder="(00) 00000-0000"
                   value="<?= e($dados['telefone']) ?>" required>
            <div class="invalid-feedback"><?= e($erros['telefone'] ?? 'Informe um telefone válido.') ?></div>
        </div>
    </div>
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço completo</label>
        <input type="text" class="form-control <?= isset($erros['endereco']) ? 'is-invalid' : '' ?>"
               id="endereco" name="endereco" maxlength="255" value="<?= e($dados['endereco']) ?>" required>
        <div class="invalid-feedback"><?= e($erros['endereco'] ?? 'Informe seu endereço completo.') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>"
                   id="senha" name="senha" minlength="8" required>
            <div class="form-text">Mínimo 8 caracteres, com maiúsculas, minúsculas e números.</div>
            <div class="invalid-feedback"><?= e($erros['senha'] ?? 'Senha inválida.') ?></div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="senha_confirmacao" class="form-label">Confirmar senha</label>
            <input type="password" class="form-control" id="senha_confirmacao" name="senha_confirmacao" minlength="8" required>
            <div class="invalid-feedback">As senhas não conferem.</div>
        </div>
    </div>
    <button type="submit" class="btn btn-carlores w-100">Criar conta</button>
</form>
<div class="text-center mt-3 small">
    Já tem conta? <a href="/auth/login.php">Entrar</a>
</div>
<?php require ROOT_PATH . '/views/partials/footer_auth.php'; ?>
