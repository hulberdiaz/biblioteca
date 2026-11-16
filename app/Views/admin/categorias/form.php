<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<form class="admin-form" action="<?= site_url($id === null ? 'admin/categorias' : 'admin/categorias/' . $id) ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($id !== null): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
    <?php if ($errores !== []): ?><div class="notice notice-error"><ul><?php foreach ($errores as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
    <div class="admin-form-grid">
        <label>Nombre<input name="nombre" maxlength="120" value="<?= esc((string) ($categoria['nombre'] ?? '')) ?>" required></label>
        <label>Slug (opcional)<input name="slug" maxlength="140" value="<?= esc((string) ($categoria['slug'] ?? '')) ?>" placeholder="Se genera desde el nombre"></label>
        <label class="wide-field">Descripción<textarea name="descripcion" rows="4"><?= esc((string) ($categoria['descripcion'] ?? '')) ?></textarea></label>
    </div>
    <div class="form-actions"><button class="button" type="submit">Guardar categoría</button><a class="button button-outline" href="<?= site_url('admin/categorias') ?>">Cancelar</a></div>
</form>
<?= $this->endSection() ?>
