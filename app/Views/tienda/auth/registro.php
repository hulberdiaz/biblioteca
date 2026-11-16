<?= $this->extend('tienda/layout') ?>

<?= $this->section('content') ?>
<section class="auth-wrap">
    <div class="auth-intro"><span class="eyebrow eyebrow-light">UN ESPACIO PARA LECTORES</span><h1>Crea tu cuenta y guarda un lugar para nuevas historias.</h1><p>Tu cuenta te permite confirmar pedidos y guardar tus datos.</p></div>
    <form class="form-card auth-card" action="<?= site_url('registro') ?>" method="post">
        <?= csrf_field() ?>
        <span class="eyebrow">NUEVO LECTOR</span><h2>Crear cuenta</h2>
        <label>Nombre completo<input type="text" name="nombre" value="<?= esc((string) old('nombre')) ?>" maxlength="160" autocomplete="name" required></label>
        <label>Correo electrónico<input type="email" name="email" value="<?= esc((string) old('email')) ?>" maxlength="190" autocomplete="email" required></label>
        <label>Contraseña (mínimo 8 caracteres)<input type="password" name="password" minlength="8" autocomplete="new-password" required></label>
        <label>Confirmar contraseña<input type="password" name="password_confirm" minlength="8" autocomplete="new-password" required></label>
        <button class="button button-full" type="submit">Crear cuenta</button>
        <p class="form-footnote">¿Ya tienes cuenta? <a href="<?= site_url('login') ?>">Inicia sesión</a></p>
    </form>
</section>
<?= $this->endSection() ?>
