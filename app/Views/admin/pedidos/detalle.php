<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<div class="order-detail-grid">
    <section class="order-panel"><span class="eyebrow">INFORMACIÓN DEL PEDIDO</span><h2>Pedido #<?= (int) $pedido['id'] ?></h2>
        <p><strong>Cliente:</strong> <?= esc($pedido['usuario_nombre']) ?> (<?= esc($pedido['usuario_email']) ?>)</p>
        <p><strong>Fecha:</strong> <?= esc((string) $pedido['created_at']) ?></p>
        <p><strong>Estado:</strong> <span class="status-pill"><?= esc(ucfirst($pedido['estado'])) ?></span></p>
        <p><strong>Dirección:</strong><br><?= nl2br(esc((string) $pedido['direccion_envio'])) ?></p>
    </section>
    <section class="order-panel"><span class="eyebrow">LIBROS</span>
        <?php foreach ($detalles as $detalle): ?>
            <div class="summary-product"><span><?= (int) $detalle['cantidad'] ?> × <?= esc($detalle['titulo_libro']) ?><small>$<?= number_format((float) $detalle['precio_unitario'], 2) ?> c/u</small></span><strong>$<?= number_format((float) $detalle['subtotal'], 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-row summary-total"><span>Total</span><strong>$<?= number_format((float) $pedido['total'], 2) ?></strong></div>
    </section>
</div>
<a class="button button-outline" href="<?= site_url('admin/pedidos') ?>">Volver a pedidos</a>
<?= $this->endSection() ?>
