<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="page-heading">
    <span class="eyebrow">TU SELECCIÓN</span>
    <h1>Carrito de compras</h1>
    <p>Revisa tus libros antes de continuar.</p>
</section>

<?php if ($lineas === []): ?>
    <div class="empty-state"><span class="empty-icon">▱</span><h2>Tu carrito está vacío</h2><p>Encuentra una historia para llevar contigo.</p><a class="button" href="<?= site_url('catalogo') ?>">Explorar libros</a></div>
<?php else: ?>
    <div class="cart-layout">
        <div class="cart-lines">
            <?php foreach ($lineas as $libro): ?>
                <article class="cart-line">
                    <a class="cart-thumb" href="<?= site_url('libro/' . rawurlencode($libro['slug'])) ?>">
                        <?php if (! empty($libro['portada'])): ?><img src="<?= esc($libro['portada'], 'attr') ?>" alt="Portada de <?= esc($libro['titulo']) ?>"><?php else: ?><span>H</span><?php endif ?>
                    </a>
                    <div class="cart-book"><a href="<?= site_url('libro/' . rawurlencode($libro['slug'])) ?>"><strong><?= esc($libro['titulo']) ?></strong></a><span><?= esc($libro['autor_nombre']) ?></span><span class="price">$<?= number_format((float) $libro['precio'], 2) ?></span></div>
                    <form class="quantity-form" action="<?= site_url('carrito/actualizar/' . (int) $libro['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <label class="sr-only" for="cantidad-<?= (int) $libro['id'] ?>">Cantidad</label>
                        <input id="cantidad-<?= (int) $libro['id'] ?>" type="number" name="cantidad" value="<?= (int) $libro['cantidad'] ?>" min="1" max="<?= (int) $libro['stock'] ?>">
                        <button class="button button-small button-outline" type="submit">Actualizar</button>
                    </form>
                    <strong class="cart-subtotal">$<?= number_format((float) $libro['subtotal'], 2) ?></strong>
                    <form action="<?= site_url('carrito/eliminar/' . (int) $libro['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="remove-button" type="submit" aria-label="Eliminar <?= esc($libro['titulo']) ?>">×</button>
                    </form>
                </article>
            <?php endforeach ?>
        </div>
        <aside class="cart-summary">
            <h2>Resumen</h2>
            <div class="summary-row"><span>Subtotal</span><strong>$<?= number_format($total, 2) ?></strong></div>
            <div class="summary-row"><span>Envío</span><span>Se coordina al confirmar</span></div>
            <div class="summary-row summary-total"><span>Total</span><strong>$<?= number_format($total, 2) ?></strong></div>
            <a class="button button-full" href="<?= site_url('checkout') ?>">Continuar compra</a>
            <a class="text-link centered" href="<?= site_url('catalogo') ?>">Seguir explorando</a>
        </aside>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
