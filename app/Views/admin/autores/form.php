<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<form class="admin-form" action="<?= site_url($id === null ? 'admin/autores' : 'admin/autores/' . $id) ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($id !== null): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
    <?php if ($errores !== []): ?><div class="notice notice-error"><ul><?php foreach ($errores as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
    <div class="admin-form-grid">
        <label>Nombre<input name="nombre" maxlength="160" value="<?= esc((string) ($autor['nombre'] ?? '')) ?>" required></label>
        <label>Foto (URL)<input type="url" name="foto" maxlength="255" value="<?= esc((string) ($autor['foto'] ?? '')) ?>" placeholder="https://..."></label>
        <label class="wide-field">Biografía<textarea name="biografia" rows="5"><?= esc((string) ($autor['biografia'] ?? '')) ?></textarea></label>
    </div>
    <div class="form-actions"><button class="button" type="submit">Guardar autor</button><a class="button button-outline" href="<?= site_url('admin/autores') ?>">Cancelar</a></div>
</form>
<?= $this->endSection() ?>
