<article class="book-card">
    <a class="book-cover" href="<?= site_url('libro/' . rawurlencode($libro['slug'])) ?>">
        <?php if (! empty($libro['portada'])): ?>
            <img src="<?= esc($libro['portada'], 'attr') ?>" alt="Portada de <?= esc($libro['titulo']) ?>" loading="lazy">
        <?php else: ?>
            <span class="cover-placeholder"><span>LECTURAS</span><strong><?= esc($libro['titulo']) ?></strong></span>
        <?php endif ?>
    </a>
    <div class="book-card-body">
        <?php if (! empty($libro['categoria_nombre'])): ?><span class="eyebrow"><?= esc($libro['categoria_nombre']) ?></span><?php endif ?>
        <h3><a href="<?= site_url('libro/' . rawurlencode($libro['slug'])) ?>"><?= esc($libro['titulo']) ?></a></h3>
        <?php if (! empty($libro['autor_nombre'])): ?><p class="muted"><?= esc($libro['autor_nombre']) ?></p><?php endif ?>
        <div class="book-card-bottom">
            <strong class="price">$<?= number_format((float) $libro['precio'], 2) ?></strong>
            <a class="button button-small button-outline" href="<?= site_url('libro/' . rawurlencode($libro['slug'])) ?>">Ver libro</a>
        </div>
    </div>
</article>
