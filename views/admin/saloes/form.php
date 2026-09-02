<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= csrfField() ?>
            <?php if ($modoEdicao): ?><input type="hidden" name="id" value="<?= (int)$dados['id'] ?>"><?php endif; ?>

            <?php if (!empty($erros['geral'])): ?><div class="alert alert-danger"><?= e($erros['geral']) ?></div><?php endif; ?>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do salão</label>
                <input type="text" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                       id="nome" name="nome" maxlength="100" value="<?= e($dados['nome']) ?>" required>
                <div class="invalid-feedback"><?= e($erros['nome'] ?? 'Informe o nome do salão.') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capacidade" class="form-label">Capacidade (convidados)</label>
                    <input type="number" min="1" step="1" class="form-control <?= isset($erros['capacidade']) ? 'is-invalid' : '' ?>"
                           id="capacidade" name="capacidade" value="<?= e((string)$dados['capacidade']) ?>" required>
                    <div class="invalid-feedback"><?= e($erros['capacidade'] ?? 'Informe a capacidade.') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="valor_base" class="form-label">Valor base (R$)</label>
                    <input type="number" min="0.01" step="0.01" class="form-control <?= isset($erros['valor_base']) ? 'is-invalid' : '' ?>"
                           id="valor_base" name="valor_base" value="<?= e((string)$dados['valor_base']) ?>" required>
                    <div class="invalid-feedback"><?= e($erros['valor_base'] ?? 'Informe o valor base.') ?></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" maxlength="1000"><?= e($dados['descricao'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto do salão (opcional, JPG/PNG até 2MB)</label>
                <input type="file" class="form-control <?= isset($erros['foto']) ? 'is-invalid' : '' ?>" id="foto" name="foto" accept="image/jpeg,image/png">
                <div class="invalid-feedback"><?= e($erros['foto'] ?? '') ?></div>
                <?php if (!empty($dados['foto'])): ?>
                    <div class="mt-2"><img src="<?= e($dados['foto']) ?>" alt="Foto atual" style="max-height:120px;border-radius:.5rem;"></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-carlores">Salvar</button>
            <a href="/admin/saloes/index.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
