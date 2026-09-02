<?php require ROOT_PATH . '/views/partials/header_admin.php'; ?>
<div class="card card-carlores mb-4">
    <div class="card-header">Dados do cliente</div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Nome</dt><dd class="col-sm-9"><?= e($cliente['nome']) ?></dd>
            <dt class="col-sm-3">E-mail</dt><dd class="col-sm-9"><?= e($cliente['email']) ?></dd>
            <dt class="col-sm-3">Telefone</dt><dd class="col-sm-9"><?= e(telefoneFormatado($cliente['telefone'])) ?></dd>
            <dt class="col-sm-3">CPF</dt><dd class="col-sm-9"><?= e(cpfFormatado($cliente['cpf'])) ?></dd>
            <dt class="col-sm-3">Endereço</dt><dd class="col-sm-9"><?= e($cliente['endereco']) ?></dd>
            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
                <?php if ($cliente['ativo']): ?>
                    <span class="badge text-bg-success">Ativo</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Inativo</span>
                <?php endif; ?>
            </dd>
        </dl>
    </div>
</div>
<div class="card card-carlores">
    <div class="card-header">Reservas deste cliente</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Data</th><th>Salão</th><th>Pacote</th><th>Status</th><th>Valor</th></tr></thead>
            <tbody>
            <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= dateBr($r['data_evento']) ?></td>
                    <td><?= e($r['salao_nome']) ?></td>
                    <td><?= e($r['pacote_nome']) ?></td>
                    <td><?= statusReservaBadge($r['status']) ?></td>
                    <td><?= moneyBr($r['valor_total']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservas)): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Nenhuma reserva encontrada.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require ROOT_PATH . '/views/partials/footer_admin.php'; ?>
