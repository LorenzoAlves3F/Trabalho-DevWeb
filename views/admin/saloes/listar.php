<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="d-flex justify-content-end mb-3">
    <a href="/admin/saloes/criar.php" class="btn btn-carlores">+ Novo Salão</a>
</div>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Nome</th><th>Capacidade</th><th>Valor base</th><th>Status</th><th class="text-end">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($saloes as $salao): ?>
                <tr>
                    <td><?= e($salao['nome']) ?></td>
                    <td><?= (int)$salao['capacidade'] ?> convidados</td>
                    <td><?= moneyBr($salao['valor_base']) ?></td>
                    <td>
                        <?php if ($salao['ativo']): ?>
                            <span class="badge text-bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="/admin/saloes/editar.php?id=<?= (int)$salao['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a>
                        <form method="post" action="/admin/saloes/excluir.php" class="d-inline"
                              onsubmit="return confirm('<?= $salao['ativo'] ? 'Desativar' : 'Reativar' ?> este salão?');">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int)$salao['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><?= $salao['ativo'] ? 'Desativar' : 'Reativar' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($saloes)): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Nenhum salão cadastrado.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
