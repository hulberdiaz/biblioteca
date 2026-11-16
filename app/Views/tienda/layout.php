<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($titulo ?? 'Librería Horizonte') ?> | Librería Horizonte</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/tienda.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="container header-row">
        <a class="brand" href="<?= site_url() ?>"><span class="brand-mark">H</span><span>Librería <strong>Horizonte</strong></span></a>
        <form class="header-search" action="<?= site_url('catalogo') ?>" method="get">
            <label class="sr-only" for="header-q">Buscar libros</label>
            <input id="header-q" type="search" name="q" value="<?= esc((string) service('request')->getGet('q')) ?>" placeholder="Buscar por libro o autor">
            <button type="submit" aria-label="Buscar">⌕</button>
        </form>
        <nav class="main-nav" aria-label="Navegación principal">
            <a href="<?= site_url('catalogo') ?>">Catálogo</a>
            <a href="<?= site_url('carrito') ?>">Carrito</a>
            <?php if (session()->get('rol') === 'admin'): ?>
                <a href="<?= site_url('admin') ?>">Administración</a>
            <?php endif ?>
            <?php if (session()->get('user_id') !== null): ?>
                <span class="nav-greeting">Hola, <?= esc((string) session()->get('nombre')) ?></span>
                <form action="<?= site_url('logout') ?>" method="post" class="inline-form">
                    <?= csrf_field() ?>
                    <button class="nav-link" type="submit">Salir</button>
                </form>
            <?php else: ?>
                <a href="<?= site_url('login') ?>">Ingresar</a>
            <?php endif ?>
        </nav>
    </div>
</header>

<main class="container main-content">
    <?php if ($mensaje = session()->getFlashdata('success')): ?>
        <div class="notice notice-success"><?= esc($mensaje) ?></div>
    <?php endif ?>
    <?php if ($mensaje = session()->getFlashdata('error')): ?>
        <div class="notice notice-error"><?= esc($mensaje) ?></div>
    <?php endif ?>
    <?php $erroresFlash = session()->getFlashdata('errors'); ?>
    <?php if (is_array($erroresFlash) && $erroresFlash !== []): ?>
        <div class="notice notice-error">
            <strong>Revisa estos datos:</strong>
            <ul><?php foreach ($erroresFlash as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
        </div>
    <?php endif ?>

    <?= $this->renderSection('content') ?>
</main>

<footer class="site-footer">
    <div class="container footer-row">
        <span>Librería Horizonte</span>
        <span>Historias para acompañar cada camino.</span>
    </div>
</footer>
</body>
</html>
