<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores">
    <div class="card-body">
        <form method="post" class="needs-validation" novalidate>
            <?= csrfField() ?>
            <?php if ($modoEdicao): ?><input type="hidden" name="id" value="<?= (int)$dados['id'] ?>"><?php endif; ?>

            <?php if (!empty($erros['geral'])): ?><div class="alert alert-danger"><?= e($erros['geral']) ?></div><?php endif; ?>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do pacote</label>
                <input type="text" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                       id="nome" name="nome" maxlength="100" value="<?= e($dados['nome']) ?>" required>
                <div class="invalid-feedback"><?= e($erros['nome'] ?? 'Informe o nome do pacote.') ?></div>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço (R$)</label>
                <input type="number" min="0.01" step="0.01" class="form-control <?= isset($erros['preco']) ? 'is-invalid' : '' ?>"
                       id="preco" name="preco" value="<?= e((string)$dados['preco']) ?>" required>
                <div class="invalid-feedback"><?= e($erros['preco'] ?? 'Informe o preço.') ?></div>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="2" maxlength="1000"><?= e($dados['descricao'] ?? '') ?></textarea>
            </div>

            <label class="form-label">Itens inclusos no pacote</label>
            <?php if (!empty($erros['itens'])): ?><div class="text-danger small mb-2"><?= e($erros['itens']) ?></div><?php endif; ?>
            <div id="containerItens">
                <?php $listaItens = $itens ?: ['']; ?>
                <?php foreach ($listaItens as $item): ?>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="itens[]" maxlength="150" placeholder="Ex: Decoração temática" value="<?= e($item) ?>">
                        <button type="button" class="btn btn-outline-danger btn-remover-item">Remover</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="btnAdicionarItem" class="btn btn-sm btn-outline-carlores mb-3">+ Adicionar item</button>
            <br>
            <button type="submit" class="btn btn-carlores">Salvar</button>
            <a href="/admin/pacotes/index.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
<script src="/assets/js/pacote-itens.js"></script>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
