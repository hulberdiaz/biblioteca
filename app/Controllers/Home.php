<?php

namespace App\Controllers;

use App\Models\LibroModel;

class Home extends BaseController
{
    public function index(): string
    {
        $libros = model(LibroModel::class);

        return view('tienda/inicio', [
            'destacados' => $libros->getDestacados(6),
            'novedades'  => $libros->getNovedades(6),
            'titulo'     => 'Librería Horizonte',
        ]);
    }
}
