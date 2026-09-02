<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores">
    <div class="card-body">
        <form method="post" class="needs-validation" novalidate>
            <?= csrfField() ?>
            <?php if ($modoEdicao): ?><input type="hidden" name="id" value="<?= (int)$dados['id'] ?>"><?php endif; ?>

            <?php if (!empty($erros['geral'])): ?><div class="alert alert-danger"><?= e($erros['geral']) ?></div><?php endif; ?>

            <?php if (!$modoEdicao): ?>
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select <?= isset($erros['cliente_id']) ? 'is-invalid' : '' ?>" id="cliente_id" name="cliente_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= (string)($dados['cliente_id'] ?? '') === (string)$c['id'] ? 'selected' : '' ?>>
                            <?= e($c['nome']) ?> - <?= e($c['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($erros['cliente_id'] ?? 'Selecione um cliente.') ?></div>
            </div>
            <?php else: ?>
                <p><strong>Cliente:</strong> <?= e($dados['cliente_nome']) ?> (<?= e($dados['cliente_email']) ?>)</p>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salao_id" class="form-label">Salão</label>
                    <select class="form-select <?= isset($erros['salao_id']) ? 'is-invalid' : '' ?>" id="salao_id" name="salao_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($saloes as $s): ?>
                            <option value="<?= (int)$s['id'] ?>" data-capacidade="<?= (int)$s['capacidade'] ?>"
                                <?= (string)($dados['salao_id'] ?? '') === (string)$s['id'] ? 'selected' : '' ?>>
                                <?= e($s['nome']) ?> (até <?= (int)$s['capacidade'] ?>, <?= moneyBr($s['valor_base']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e($erros['salao_id'] ?? 'Selecione um salão.') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pacote_id" class="form-label">Pacote</label>
                    <select class="form-select <?= isset($erros['pacote_id']) ? 'is-invalid' : '' ?>" id="pacote_id" name="pacote_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($pacotes as $p): ?>
                            <option value="<?= (int)$p['id'] ?>" <?= (string)($dados['pacote_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>>
                                <?= e($p['nome']) ?> (<?= moneyBr($p['preco']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e($erros['pacote_id'] ?? 'Selecione um pacote.') ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="data_evento" class="form-label">Data do evento</label>
                    <input type="date" class="form-control <?= isset($erros['data_evento']) ? 'is-invalid' : '' ?>"
                           id="data_evento" name="data_evento" min="<?= date('Y-m-d') ?>" value="<?= e($dados['data_evento'] ?? '') ?>" required>
                    <div class="invalid-feedback"><?= e($erros['data_evento'] ?? 'Informe uma data válida.') ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="turno" class="form-label">Turno</label>
                    <select class="form-select <?= isset($erros['turno']) ? 'is-invalid' : '' ?>" id="turno" name="turno" required>
                        <option value="">Selecione...</option>
                        <?php foreach (TURNOS as $valor => $rotulo): ?>
                            <option value="<?= e($valor) ?>" <?= ($dados['turno'] ?? '') === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e($erros['turno'] ?? 'Selecione um turno.') ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="numero_convidados" class="form-label">Nº de convidados</label>
                    <input type="number" min="1" class="form-control <?= isset($erros['numero_convidados']) ? 'is-invalid' : '' ?>"
                           id="numero_convidados" name="numero_convidados" value="<?= e((string)($dados['numero_convidados'] ?? '')) ?>" required>
                    <div id="avisoCapacidade" class="form-text"></div>
                    <div class="invalid-feedback"><?= e($erros['numero_convidados'] ?? 'Informe o número de convidados.') ?></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="tipo_evento" class="form-label">Tipo de evento</label>
                <input type="text" class="form-control <?= isset($erros['tipo_evento']) ? 'is-invalid' : '' ?>"
                       id="tipo_evento" name="tipo_evento" maxlength="80" placeholder="Ex: Casamento, Aniversário de 15 anos..."
                       value="<?= e($dados['tipo_evento'] ?? '') ?>" required>
                <div class="invalid-feedback"><?= e($erros['tipo_evento'] ?? 'Informe o tipo de evento.') ?></div>
            </div>
            <?php if ($modoEdicao): ?>
            <div class="mb-3 col-md-4">
                <label for="desconto" class="form-label">Desconto (R$)</label>
                <input type="number" min="0" step="0.01" class="form-control <?= isset($erros['desconto']) ? 'is-invalid' : '' ?>"
                       id="desconto" name="desconto" value="<?= e((string)($dados['desconto'] ?? '0')) ?>">
                <div class="invalid-feedback"><?= e($erros['desconto'] ?? '') ?></div>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="2" maxlength="1000"><?= e($dados['observacoes'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-carlores">Salvar</button>
            <a href="/admin/reservas/index.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
<script src="/assets/js/reserva-form.js"></script>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
