<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('tienda/auth/login', ['titulo' => 'Iniciar sesión']);
    }

    public function procesarLogin()
    {
        $reglas = [
            'email'    => 'required|valid_email|max_length[190]',
            'password' => 'required',
        ];

        if (! $this->validate($reglas)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $usuario = model(UsuarioModel::class)->findByEmail(mb_strtolower(trim((string) $this->request->getPost('email'))));
        $clave   = (string) $this->request->getPost('password');

        if ($usuario === null || ! password_verify($clave, $usuario['password'])) {
            return redirect()->back()->with('error', 'Correo o contraseña incorrectos.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => (int) $usuario['id'],
            'nombre'  => $usuario['nombre'],
            'email'   => $usuario['email'],
            'rol'     => $usuario['rol'],
        ]);

        return redirect()->to($usuario['rol'] === 'admin' ? site_url('admin') : site_url())
            ->with('success', '¡Bienvenido, ' . $usuario['nombre'] . '!');
    }

    public function registro(): string
    {
        return view('tienda/auth/registro', ['titulo' => 'Crear cuenta']);
    }

    public function procesarRegistro()
    {
        $reglas = [
            'nombre'            => 'required|max_length[160]',
            'email'             => 'required|valid_email|max_length[190]|is_unique[usuarios.email]',
            'password'          => 'required|min_length[8]|max_length[255]',
            'password_confirm'  => 'required|matches[password]',
        ];

        if (! $this->validate($reglas)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $usuarios = model(UsuarioModel::class);
        $id       = $usuarios->insert([
            'nombre'   => trim((string) $this->request->getPost('nombre')),
            'email'    => mb_strtolower(trim((string) $this->request->getPost('email'))),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'rol'      => 'cliente',
        ]);

        if ($id === false) {
            return redirect()->back()->with('errors', $usuarios->errors());
        }

        return redirect()->to(site_url('login'))->with('success', 'Tu cuenta se creó correctamente. Ya puedes iniciar sesión.');
    }

    public function logout()
    {
        session()->remove(['user_id', 'nombre', 'email', 'rol']);
        session()->regenerate(true);

        return redirect()->to(site_url())->with('success', 'Has cerrado sesión.');
    }
}
