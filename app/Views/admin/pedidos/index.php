<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<p class="admin-description">Pedidos registrados por los clientes. Todos quedan pendientes hasta coordinar el pago.</p>
<div class="table-wrap"><table class="data-table">
    <thead><tr><th>Pedido</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Total</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pedidos as $pedido): ?>
        <tr><td><strong>#<?= (int) $pedido['id'] ?></strong></td><td><?= esc($pedido['usuario_nombre']) ?><small><?= esc($pedido['usuario_email']) ?></small></td>
            <td><?= esc((string) $pedido['created_at']) ?></td><td><span class="status-pill"><?= esc(ucfirst($pedido['estado'])) ?></span></td>
            <td>$<?= number_format((float) $pedido['total'], 2) ?></td><td><a class="button button-small button-outline" href="<?= site_url('admin/pedidos/' . (int) $pedido['id']) ?>">Ver pedido</a></td></tr>
    <?php endforeach ?>
    <?php if ($pedidos === []): ?><tr><td colspan="6" class="table-empty">Todavía no hay pedidos.</td></tr><?php endif ?>
    </tbody>
</table></div>
<div class="pager-wrap"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
