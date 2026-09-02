<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="d-flex justify-content-end mb-3">
    <a href="/admin/pacotes/criar.php" class="btn btn-carlores">+ Novo Pacote</a>
</div>
<div class="row g-3">
<?php foreach ($pacotes as $pacote): ?>
    <div class="col-md-4">
        <div class="card card-carlores h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?= e($pacote['nome']) ?></span>
                <?php if ($pacote['ativo']): ?>
                    <span class="badge text-bg-success">Ativo</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Inativo</span>
                <?php endif; ?>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="fs-4 fw-bold mb-2"><?= moneyBr($pacote['preco']) ?></div>
                <p class="text-muted small"><?= e($pacote['descricao']) ?></p>
                <ul class="small mb-3">
                    <?php foreach ($itensPorPacote[$pacote['id']] ?? [] as $item): ?>
                        <li><?= e($item['descricao_item']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-auto d-flex gap-2">
                    <a href="/admin/pacotes/editar.php?id=<?= (int)$pacote['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a>
                    <form method="post" action="/admin/pacotes/excluir.php"
                          onsubmit="return confirm('<?= $pacote['ativo'] ? 'Desativar' : 'Reativar' ?> este pacote?');">
                        <?= csrfField() ?>
                        <input type="hidden" name="id" value="<?= (int)$pacote['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger"><?= $pacote['ativo'] ? 'Desativar' : 'Reativar' ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($pacotes)): ?>
    <div class="col-12"><div class="text-center text-muted py-5">Nenhum pacote cadastrado.</div></div>
<?php endif; ?>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
