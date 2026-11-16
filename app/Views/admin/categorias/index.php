<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<div class="admin-toolbar"><p>Organiza el catálogo por temas.</p><a class="button" href="<?= site_url('admin/categorias/nuevo') ?>">＋ Nueva categoría</a></div>
<div class="table-wrap"><table class="data-table">
    <thead><tr><th>Categoría</th><th>Slug</th><th>Descripción</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($categorias as $categoria): ?>
        <tr><td><strong><?= esc($categoria['nombre']) ?></strong></td><td><?= esc($categoria['slug']) ?></td><td><?= esc($categoria['descripcion'] ?? '') ?></td>
            <td class="table-actions"><a class="button button-small button-outline" href="<?= site_url('admin/categorias/editar/' . (int) $categoria['id']) ?>">Editar</a>
                <form action="<?= site_url('admin/categorias/' . (int) $categoria['id']) ?>" method="post" onsubmit="return confirm('¿Eliminar esta categoría?')"><?= csrf_field() ?><input type="hidden" name="_method" value="DELETE"><button class="button button-small button-danger" type="submit">Eliminar</button></form>
            </td></tr>
    <?php endforeach ?>
    <?php if ($categorias === []): ?><tr><td colspan="4" class="table-empty">No hay categorías registradas.</td></tr><?php endif ?>
    </tbody>
</table></div>
<?= $this->endSection() ?>
