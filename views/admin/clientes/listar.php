<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="d-flex justify-content-end mb-3">
    <a href="/admin/clientes/criar.php" class="btn btn-carlores">+ Novo Cliente</a>
</div>
<div class="card card-carlores">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>CPF</th><th>Status</th><th class="text-end">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= e($c['nome']) ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e(telefoneFormatado($c['telefone'])) ?></td>
                    <td><?= e(cpfFormatado($c['cpf'])) ?></td>
                    <td>
                        <?php if ($c['ativo']): ?>
                            <span class="badge text-bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="/admin/clientes/visualizar.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-carlores">Ver</a>
                        <a href="/admin/clientes/editar.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-carlores">Editar</a>
                        <form method="post" action="/admin/clientes/excluir.php" class="d-inline"
                              onsubmit="return confirm('<?= $c['ativo'] ? 'Desativar' : 'Reativar' ?> este cliente?');">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int)$c['usuario_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><?= $c['ativo'] ? 'Desativar' : 'Reativar' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($clientes)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Nenhum cliente cadastrado.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
