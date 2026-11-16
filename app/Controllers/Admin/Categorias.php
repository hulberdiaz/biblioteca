<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\LibroModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Categorias extends BaseController
{
    public function index(): string
    {
        return view('admin/categorias/index', [
            'titulo'     => 'Administrar categorías',
            'categorias' => model(CategoriaModel::class)->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo(): string
    {
        return view('admin/categorias/form', ['titulo' => 'Nueva categoría', 'categoria' => [], 'errores' => [], 'id' => null]);
    }

    public function crear()
    {
        $modelo = model(CategoriaModel::class);
        $datos  = $this->datosCategoria();
        if (! $modelo->insert($datos)) {
            return view('admin/categorias/form', ['titulo' => 'Nueva categoría', 'categoria' => $datos, 'errores' => $modelo->errors(), 'id' => null]);
        }

        return redirect()->to(site_url('admin/categorias'))->with('success', 'Categoría creada.');
    }

    public function editar(int $id): string
    {
        $categoria = model(CategoriaModel::class)->find($id);
        if ($categoria === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró la categoría.');
        }

        return view('admin/categorias/form', ['titulo' => 'Editar categoría', 'categoria' => $categoria, 'errores' => [], 'id' => $id]);
    }

    public function actualizar(int $id)
    {
        $modelo = model(CategoriaModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró la categoría.');
        }

        $datos = $this->datosCategoria();
        if (! $modelo->update($id, $datos)) {
            return view('admin/categorias/form', ['titulo' => 'Editar categoría', 'categoria' => $datos, 'errores' => $modelo->errors(), 'id' => $id]);
        }

        return redirect()->to(site_url('admin/categorias'))->with('success', 'Categoría actualizada.');
    }

    public function eliminar(int $id)
    {
        $modelo = model(CategoriaModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró la categoría.');
        }

        if (model(LibroModel::class)->where('categoria_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/categorias'))->with('error', 'No se puede borrar una categoría que contiene libros.');
        }

        if (! $modelo->delete($id)) {
            return redirect()->to(site_url('admin/categorias'))->with('error', 'No se pudo eliminar la categoría.');
        }

        return redirect()->to(site_url('admin/categorias'))->with('success', 'Categoría eliminada.');
    }

    private function datosCategoria(): array
    {
        helper('url');
        $nombre = trim((string) $this->request->getPost('nombre'));
        $slug   = trim((string) $this->request->getPost('slug'));

        return [
            'nombre'      => $nombre,
            'slug'        => $slug !== '' ? url_title($slug, '-', true) : url_title($nombre, '-', true),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
        ];
    }
}
