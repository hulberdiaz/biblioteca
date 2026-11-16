<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<p class="breadcrumb"><a href="<?= site_url('catalogo') ?>">Catálogo</a><span>/</span><?= esc($libro['titulo']) ?></p>
<section class="detail-grid">
    <div class="detail-cover book-cover">
        <?php if (! empty($libro['portada'])): ?>
            <img src="<?= esc($libro['portada'], 'attr') ?>" alt="Portada de <?= esc($libro['titulo']) ?>">
        <?php else: ?>
            <span class="cover-placeholder"><span><?= esc($libro['categoria_nombre']) ?></span><strong><?= esc($libro['titulo']) ?></strong><small><?= esc($libro['autor_nombre']) ?></small></span>
        <?php endif ?>
    </div>
    <div class="detail-copy">
        <span class="eyebrow"><?= esc($libro['categoria_nombre']) ?><?= (int) $libro['destacado'] === 1 ? ' · DESTACADO' : '' ?></span>
        <h1><?= esc($libro['titulo']) ?></h1>
        <p class="detail-author">Por <?= esc($libro['autor_nombre']) ?></p>
        <p class="detail-description"><?= nl2br(esc($libro['sinopsis'] ?: 'Una nueva historia te espera.')) ?></p>
        <?php if (! empty($libro['isbn'])): ?><p class="muted">ISBN: <?= esc($libro['isbn']) ?></p><?php endif ?>
        <div class="detail-purchase">
            <strong class="price price-large">$<?= number_format((float) $libro['precio'], 2) ?></strong>
            <?php if ((int) $libro['stock'] > 0): ?>
                <form action="<?= site_url('carrito/agregar/' . (int) $libro['id']) ?>" method="post" class="add-form">
                    <?= csrf_field() ?>
                    <label>Cantidad <input type="number" name="cantidad" value="1" min="1" max="<?= (int) $libro['stock'] ?>" required></label>
                    <button class="button" type="submit">Agregar al carrito</button>
                </form>
                <span class="stock-note"><?= (int) $libro['stock'] ?> disponibles</span>
            <?php else: ?>
                <span class="stock-note stock-empty">Agotado por ahora</span>
            <?php endif ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
