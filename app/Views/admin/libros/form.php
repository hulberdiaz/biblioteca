<?= $this->extend('admin/layout') ?>

<?= $this->section('adminContent') ?>
<form class="admin-form" action="<?= site_url($id === null ? 'admin/libros' : 'admin/libros/' . $id) ?>" method="post">
    <?= csrf_field() ?>
    <?php if ($id !== null): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
    <?php if ($errores !== []): ?><div class="notice notice-error"><ul><?php foreach ($errores as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
    <div class="admin-form-grid">
        <label>Título<input name="titulo" maxlength="200" value="<?= esc((string) ($libro['titulo'] ?? '')) ?>" required></label>
        <label>Slug (opcional)<input name="slug" maxlength="220" value="<?= esc((string) ($libro['slug'] ?? '')) ?>" placeholder="Se genera desde el título"></label>
        <label>Autor<select name="autor_id" required><option value="">Selecciona un autor</option><?php foreach ($autores as $autor): ?><option value="<?= (int) $autor['id'] ?>" <?= (string) ($libro['autor_id'] ?? '') === (string) $autor['id'] ? 'selected' : '' ?>><?= esc($autor['nombre']) ?></option><?php endforeach ?></select></label>
        <label>Categoría<select name="categoria_id" required><option value="">Selecciona una categoría</option><?php foreach ($categorias as $categoria): ?><option value="<?= (int) $categoria['id'] ?>" <?= (string) ($libro['categoria_id'] ?? '') === (string) $categoria['id'] ? 'selected' : '' ?>><?= esc($categoria['nombre']) ?></option><?php endforeach ?></select></label>
        <label>Precio<input type="number" name="precio" min="0" step="0.01" value="<?= esc((string) ($libro['precio'] ?? '0.00')) ?>" required></label>
        <label>Stock<input type="number" name="stock" min="0" step="1" value="<?= esc((string) ($libro['stock'] ?? '0')) ?>" required></label>
        <label>ISBN<input name="isbn" maxlength="20" value="<?= esc((string) ($libro['isbn'] ?? '')) ?>"></label>
        <label>URL de portada<input type="url" name="portada" maxlength="255" value="<?= esc((string) ($libro['portada'] ?? '')) ?>" placeholder="https://..."></label>
        <label class="admin-checkbox"><input type="checkbox" name="destacado" value="1" <?= (int) ($libro['destacado'] ?? 0) === 1 ? 'checked' : '' ?>> Mostrar como destacado</label>
        <label class="wide-field">Sinopsis<textarea name="sinopsis" rows="5"><?= esc((string) ($libro['sinopsis'] ?? '')) ?></textarea></label>
    </div>
    <div class="form-actions"><button class="button" type="submit">Guardar libro</button><a class="button button-outline" href="<?= site_url('admin/libros') ?>">Cancelar</a></div>
</form>
<?= $this->endSection() ?>
