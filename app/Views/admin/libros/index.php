<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<div class="admin-toolbar"><p>Gestiona el catálogo, el inventario y los precios.</p><a class="button" href="<?= site_url('admin/libros/nuevo') ?>">＋ Nuevo libro</a></div>
<div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>Libro</th><th>Categoría</th><th>Autor</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($libros as $libro): ?>
            <tr>
                <td><strong><?= esc($libro['titulo']) ?></strong><small><?= esc($libro['slug']) ?></small></td>
                <td><?= esc($libro['categoria_nombre']) ?></td>
                <td><?= esc($libro['autor_nombre']) ?></td>
                <td>$<?= number_format((float) $libro['precio'], 2) ?></td>
                <td><?= (int) $libro['stock'] ?></td>
                <td class="table-actions">
                    <a class="button button-small button-outline" href="<?= site_url('admin/libros/editar/' . (int) $libro['id']) ?>">Editar</a>
                    <form action="<?= site_url('admin/libros/' . (int) $libro['id']) ?>" method="post" onsubmit="return confirm('¿Eliminar este libro?')">
                        <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                        <button class="button button-small button-danger" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        <?php if ($libros === []): ?><tr><td colspan="6" class="table-empty">Aún no hay libros. Importa los datos de ejemplo o crea el primero.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<div class="pager-wrap"><?= $pager->links() ?></div>
<?= $this->endSection() ?>
