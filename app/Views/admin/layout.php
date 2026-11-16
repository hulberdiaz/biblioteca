<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="admin-heading">
    <div><span class="eyebrow">PANEL DE CONTROL</span><h1><?= esc($titulo ?? 'Administración') ?></h1></div>
    <nav class="admin-nav" aria-label="Administración">
        <a href="<?= site_url('admin/libros') ?>">Libros</a>
        <a href="<?= site_url('admin/categorias') ?>">Categorías</a>
        <a href="<?= site_url('admin/autores') ?>">Autores</a>
        <a href="<?= site_url('admin/pedidos') ?>">Pedidos</a>
    </nav>
</section>
<?= $this->renderSection('adminContent') ?>
<?= $this->endSection() ?>
