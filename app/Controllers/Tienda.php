<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\LibroModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Tienda extends BaseController
{
    public function index(): string
    {
        $librosModel = model(LibroModel::class);
        $builder     = $librosModel->withRelations();
        $busqueda    = trim((string) $this->request->getGet('q'));
        $categoria   = $this->request->getGet('categoria');

        if ($busqueda !== '') {
            $builder->groupStart()
                ->like('libros.titulo', $busqueda)
                ->orLike('autores.nombre', $busqueda)
                ->orLike('libros.sinopsis', $busqueda)
                ->groupEnd();
        }

        if (is_string($categoria) && ctype_digit($categoria) && (int) $categoria > 0) {
            $builder->where('libros.categoria_id', (int) $categoria);
        }

        $libros = $builder->orderBy('libros.titulo', 'ASC')->paginate(12);

        return view('tienda/catalogo', [
            'titulo'     => 'Catálogo',
            'libros'     => $libros,
            'categorias' => model(CategoriaModel::class)->orderBy('nombre')->findAll(),
            'pager'      => $librosModel->pager,
            'busqueda'   => $busqueda,
            'categoria'  => $categoria,
        ]);
    }

    public function detalle(string $slug): string
    {
        $libro = model(LibroModel::class)->findBySlug($slug);

        if ($libro === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el libro solicitado.');
        }

        return view('tienda/detalle', [
            'titulo' => $libro['titulo'],
            'libro'  => $libro,
        ]);
    }
}
