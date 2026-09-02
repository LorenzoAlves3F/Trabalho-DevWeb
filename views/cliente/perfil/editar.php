<?php require ROOT_PATH . '/views/partials/header_cliente.php'; ?>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-carlores">
            <div class="card-header">Meus dados</div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <?= csrfField() ?>
                    <input type="hidden" name="acao" value="perfil">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" class="form-control <?= (!$mostrarSenha && isset($erros['nome'])) ? 'is-invalid' : '' ?>"
                               id="nome" name="nome" minlength="3" maxlength="120" value="<?= e($dados['nome']) ?>" required>
                        <div class="invalid-feedback"><?= e($erros['nome'] ?? 'Informe seu nome completo.') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control <?= (!$mostrarSenha && isset($erros['email'])) ? 'is-invalid' : '' ?>"
                               id="email" name="email" maxlength="150" value="<?= e($dados['email']) ?>" required>
                        <div class="invalid-feedback"><?= e($erros['email'] ?? 'Informe um e-mail válido.') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control <?= (!$mostrarSenha && isset($erros['telefone'])) ? 'is-invalid' : '' ?>"
                               id="telefone" name="telefone" data-mascara="telefone" maxlength="15"
                               value="<?= e(telefoneFormatado($dados['telefone'])) ?>" required>
                        <div class="invalid-feedback"><?= e($erros['telefone'] ?? 'Informe um telefone válido.') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control <?= (!$mostrarSenha && isset($erros['endereco'])) ? 'is-invalid' : '' ?>"
                               id="endereco" name="endereco" maxlength="255" value="<?= e($dados['endereco']) ?>" required>
                        <div class="invalid-feedback"><?= e($erros['endereco'] ?? 'Informe seu endereço.') ?></div>
                    </div>
                    <button type="submit" class="btn btn-carlores">Salvar dados</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-carlores">
            <div class="card-header">Alterar senha</div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <?= csrfField() ?>
                    <input type="hidden" name="acao" value="senha">
                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha atual</label>
                        <input type="password" class="form-control <?= ($mostrarSenha && isset($erros['senha_atual'])) ? 'is-invalid' : '' ?>"
                               id="senha_atual" name="senha_atual" required>
                        <div class="invalid-feedback"><?= e($erros['senha_atual'] ?? 'Informe sua senha atual.') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova senha</label>
                        <input type="password" class="form-control <?= ($mostrarSenha && isset($erros['nova_senha'])) ? 'is-invalid' : '' ?>"
                               id="nova_senha" name="nova_senha" minlength="8" required>
                        <div class="form-text">Mínimo 8 caracteres, com maiúsculas, minúsculas e números.</div>
                        <div class="invalid-feedback"><?= e($erros['nova_senha'] ?? 'Senha inválida.') ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="nova_senha_confirmacao" class="form-label">Confirmar nova senha</label>
                        <input type="password" class="form-control <?= ($mostrarSenha && isset($erros['nova_senha_confirmacao'])) ? 'is-invalid' : '' ?>"
                               id="nova_senha_confirmacao" name="nova_senha_confirmacao" minlength="8" required>
                        <div class="invalid-feedback"><?= e($erros['nova_senha_confirmacao'] ?? 'As senhas não conferem.') ?></div>
                    </div>
                    <button type="submit" class="btn btn-carlores">Alterar senha</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_cliente.php'; ?>
