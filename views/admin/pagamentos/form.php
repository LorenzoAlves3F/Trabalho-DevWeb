<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores">
    <div class="card-body">
        <p><strong>Reserva:</strong> #<?= (int)$reserva['id'] ?> - <?= e($reserva['cliente_nome']) ?> -
            <?= dateBr($reserva['data_evento']) ?> - <?= e($reserva['salao_nome']) ?></p>
        <p><strong>Saldo devedor atual:</strong> <?= moneyBr($saldoDevedor) ?></p>

        <?php if (!empty($erros['geral'])): ?><div class="alert alert-danger"><?= e($erros['geral']) ?></div><?php endif; ?>

        <form method="post" class="needs-validation" novalidate>
            <?= csrfField() ?>
            <input type="hidden" name="reserva_id" value="<?= (int)$reserva['id'] ?>">
            <?php if ($modoEdicao): ?><input type="hidden" name="id" value="<?= (int)$dados['id'] ?>"><?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="valor" class="form-label">Valor (R$)</label>
                    <input type="number" min="0.01" step="0.01" class="form-control <?= isset($erros['valor']) ? 'is-invalid' : '' ?>"
                           id="valor" name="valor" value="<?= e((string)$dados['valor']) ?>" required>
                    <div class="invalid-feedback"><?= e($erros['valor'] ?? 'Informe um valor válido.') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="data_pagamento" class="form-label">Data do pagamento</label>
                    <input type="date" max="<?= date('Y-m-d') ?>" class="form-control <?= isset($erros['data_pagamento']) ? 'is-invalid' : '' ?>"
                           id="data_pagamento" name="data_pagamento" value="<?= e($dados['data_pagamento']) ?>" required>
                    <div class="invalid-feedback"><?= e($erros['data_pagamento'] ?? 'A data não pode ser futura.') ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="forma_pagamento" class="form-label">Forma de pagamento</label>
                    <select class="form-select <?= isset($erros['forma_pagamento']) ? 'is-invalid' : '' ?>" id="forma_pagamento" name="forma_pagamento" required>
                        <option value="">Selecione...</option>
                        <?php foreach (FORMAS_PAGAMENTO as $valor => $rotulo): ?>
                            <option value="<?= e($valor) ?>" <?= ($dados['forma_pagamento'] ?? '') === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e($erros['forma_pagamento'] ?? 'Selecione a forma de pagamento.') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select <?= isset($erros['tipo']) ? 'is-invalid' : '' ?>" id="tipo" name="tipo" required>
                        <?php foreach (TIPOS_PAGAMENTO as $valor => $rotulo): ?>
                            <option value="<?= e($valor) ?>" <?= ($dados['tipo'] ?? '') === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e($erros['tipo'] ?? 'Selecione o tipo de pagamento.') ?></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações</label>
                <input type="text" class="form-control" id="observacoes" name="observacoes" maxlength="255" value="<?= e($dados['observacoes'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-carlores">Salvar</button>
            <a href="/admin/reservas/visualizar.php?id=<?= (int)$reserva['id'] ?>" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
