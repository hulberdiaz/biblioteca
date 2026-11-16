<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow eyebrow-light">UN LUGAR PARA CADA HISTORIA</span>
        <h1>Encuentra tu próxima gran lectura.</h1>
        <p>Explora historias, descubre nuevas voces y deja que un buen libro te lleve más lejos.</p>
        <a class="button button-light" href="<?= site_url('catalogo') ?>">Explorar catálogo <span aria-hidden="true">→</span></a>
    </div>
    <div class="hero-art" aria-hidden="true">
        <div class="hero-book hero-book-one"><span>EL JARDÍN<br>DE LAS IDEAS</span></div>
        <div class="hero-book hero-book-two"><span>MAPAS PARA<br>VOLVER</span></div>
        <div class="hero-book hero-book-three"><span>LA LUZ<br>DEL CAMINO</span></div>
    </div>
</section>

<?php if ($destacados !== []): ?>
    <section class="section-block">
        <div class="section-heading">
            <div><span class="eyebrow">SELECCIÓN DE LA CASA</span><h2>Libros destacados</h2></div>
            <a class="text-link" href="<?= site_url('catalogo') ?>">Ver todos <span aria-hidden="true">→</span></a>
        </div>
        <div class="book-grid"><?php foreach ($destacados as $libro): ?><?= view('tienda/_libro', ['libro' => $libro]) ?><?php endforeach ?></div>
    </section>
<?php endif ?>

<section class="callout">
    <div><span class="eyebrow eyebrow-light">UN BUEN LIBRO SIEMPRE ENCUENTRA SU MOMENTO</span><h2>Tu próxima aventura está a unas páginas de distancia.</h2></div>
    <a class="button button-light" href="<?= site_url('catalogo') ?>">Ver catálogo</a>
</section>

<?php if ($novedades !== []): ?>
    <section class="section-block">
        <div class="section-heading">
            <div><span class="eyebrow">RECIÉN LLEGADOS</span><h2>Novedades</h2></div>
            <a class="text-link" href="<?= site_url('catalogo') ?>">Explorar catálogo <span aria-hidden="true">→</span></a>
        </div>
        <div class="book-grid"><?php foreach ($novedades as $libro): ?><?= view('tienda/_libro', ['libro' => $libro]) ?><?php endforeach ?></div>
    </section>
<?php endif ?>
<?= $this->endSection() ?>
