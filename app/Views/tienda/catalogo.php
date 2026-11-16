<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="page-heading">
    <span class="eyebrow">LEE A TU MANERA</span>
    <h1>Catálogo de libros</h1>
    <p>Busca por título, autor o encuentra algo nuevo en tus categorías favoritas.</p>
</section>

<form class="catalog-filters" action="<?= site_url('catalogo') ?>" method="get">
    <label class="field-grow">Buscar
        <input type="search" name="q" value="<?= esc($busqueda) ?>" placeholder="Título, autor o palabra clave">
    </label>
    <label>Categoría
        <select name="categoria">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $item): ?>
                <option value="<?= (int) $item['id'] ?>" <?= (string) $categoria === (string) $item['id'] ? 'selected' : '' ?>><?= esc($item['nombre']) ?></option>
            <?php endforeach ?>
        </select>
    </label>
    <button class="button" type="submit">Buscar libros</button>
</form>

<?php if ($libros === []): ?>
    <div class="empty-state"><span class="empty-icon">⌕</span><h2>No encontramos libros</h2><p>Prueba otra búsqueda o vuelve a ver todo el catálogo.</p><a class="button button-outline" href="<?= site_url('catalogo') ?>">Ver todos</a></div>
<?php else: ?>
    <div class="book-grid"><?php foreach ($libros as $libro): ?><?= view('tienda/_libro', ['libro' => $libro]) ?><?php endforeach ?></div>
    <div class="pager-wrap"><?= $pager->links() ?></div>
<?php endif ?>
<?= $this->endSection() ?>
