<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<div class="admin-toolbar"><p>Mantén los perfiles de autores del catálogo.</p><a class="button" href="<?= site_url('admin/autores/nuevo') ?>">＋ Nuevo autor</a></div>
<div class="table-wrap"><table class="data-table">
    <thead><tr><th>Autor</th><th>Biografía</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($autores as $autor): ?>
        <tr><td><strong><?= esc($autor['nombre']) ?></strong></td><td><?= esc(mb_strimwidth((string) ($autor['biografia'] ?? ''), 0, 100, '…')) ?></td>
            <td class="table-actions"><a class="button button-small button-outline" href="<?= site_url('admin/autores/editar/' . (int) $autor['id']) ?>">Editar</a>
                <form action="<?= site_url('admin/autores/' . (int) $autor['id']) ?>" method="post" onsubmit="return confirm('¿Eliminar este autor?')"><?= csrf_field() ?><input type="hidden" name="_method" value="DELETE"><button class="button button-small button-danger" type="submit">Eliminar</button></form>
            </td></tr>
    <?php endforeach ?>
    <?php if ($autores === []): ?><tr><td colspan="3" class="table-empty">No hay autores registrados.</td></tr><?php endif ?>
    </tbody>
</table></div>
<?= $this->endSection() ?>
