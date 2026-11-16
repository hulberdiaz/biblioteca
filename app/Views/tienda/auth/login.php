<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="auth-wrap">
    <div class="auth-intro"><span class="eyebrow eyebrow-light">QUÉ BUENO VERTE</span><h1>Vuelve a tus historias favoritas.</h1><p>Inicia sesión para finalizar tus compras.</p></div>
    <form class="form-card auth-card" action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>
        <span class="eyebrow">TU CUENTA</span><h2>Iniciar sesión</h2>
        <label>Correo electrónico<input type="email" name="email" value="<?= esc((string) old('email')) ?>" autocomplete="email" required></label>
        <label>Contraseña<input type="password" name="password" autocomplete="current-password" required></label>
        <button class="button button-full" type="submit">Ingresar</button>
        <p class="form-footnote">¿Aún no tienes cuenta? <a href="<?= site_url('registro') ?>">Regístrate</a></p>
    </form>
</section>
<?= $this->endSection() ?>
