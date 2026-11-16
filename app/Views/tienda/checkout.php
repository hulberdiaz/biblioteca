<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="page-heading">
    <span class="eyebrow">ÚLTIMO PASO</span>
    <h1>Finalizar compra</h1>
    <p>Hola, <?= esc((string) session()->get('nombre')) ?>. Confirma la dirección de entrega.</p>
</section>
<div class="checkout-layout">
    <form class="form-card" action="<?= site_url('checkout') ?>" method="post">
        <?= csrf_field() ?>
        <h2>Datos de envío</h2>
        <label>Dirección completa
            <textarea name="direccion_envio" rows="4" maxlength="500" required placeholder="Calle, número, ciudad y referencias"><?= esc((string) old('direccion_envio')) ?></textarea>
        </label>
        <p class="muted">El pedido quedará pendiente; la librería se comunicará contigo para coordinar el pago y la entrega.</p>
        <button class="button button-full" type="submit">Confirmar pedido</button>
    </form>
    <aside class="cart-summary">
        <h2>Tu pedido</h2>
        <?php $total = 0.0; foreach ($productos as $producto): $total += $producto['subtotal']; ?>
            <div class="summary-product"><span><?= (int) $producto['cantidad'] ?> × <?= esc($producto['titulo']) ?></span><strong>$<?= number_format((float) $producto['subtotal'], 2) ?></strong></div>
        <?php endforeach ?>
        <div class="summary-row summary-total"><span>Total</span><strong>$<?= number_format($total, 2) ?></strong></div>
    </aside>
</div>
<?= $this->endSection() ?>
