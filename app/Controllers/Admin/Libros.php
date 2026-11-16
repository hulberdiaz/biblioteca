<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AutorModel;
use App\Models\CategoriaModel;
use App\Models\DetallePedidoModel;
use App\Models\LibroModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Libros extends BaseController
{
    public function index(): string
    {
        $librosModel = model(LibroModel::class);
        $libros = $librosModel->withRelations()->orderBy('libros.titulo')->paginate(20);

        return view('admin/libros/index', [
            'titulo' => 'Administrar libros',
            'libros' => $libros,
            'pager'  => $librosModel->pager,
        ]);
    }

    public function nuevo(): string
    {
        return view('admin/libros/form', $this->datosFormulario('Nuevo libro'));
    }

    public function crear()
    {
        $modelo = model(LibroModel::class);
        $datos  = $this->datosLibro();
        $id     = $modelo->insert($datos);

        if ($id === false) {
            return view('admin/libros/form', $this->datosFormulario('Nuevo libro', $datos, $modelo->errors()));
        }

        return redirect()->to(site_url('admin/libros'))->with('success', 'Libro creado correctamente.');
    }

    public function editar(int $id): string
    {
        $libro = model(LibroModel::class)->find($id);
        if ($libro === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el libro.');
        }

        return view('admin/libros/form', $this->datosFormulario('Editar libro', $libro, [], $id));
    }

    public function actualizar(int $id)
    {
        $modelo = model(LibroModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el libro.');
        }

        $datos = $this->datosLibro();
        if (! $modelo->update($id, $datos)) {
            return view('admin/libros/form', $this->datosFormulario('Editar libro', $datos, $modelo->errors(), $id));
        }

        return redirect()->to(site_url('admin/libros'))->with('success', 'Libro actualizado correctamente.');
    }

    public function eliminar(int $id)
    {
        $modelo = model(LibroModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el libro.');
        }

        if (model(DetallePedidoModel::class)->where('libro_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/libros'))
                ->with('error', 'No se puede eliminar un libro que aparece en pedidos; puedes dejar su stock en cero.');
        }

        if (! $modelo->delete($id)) {
            return redirect()->to(site_url('admin/libros'))->with('error', 'No se pudo eliminar el libro.');
        }

        return redirect()->to(site_url('admin/libros'))->with('success', 'Libro eliminado.');
    }

    private function datosLibro(): array
    {
        helper('url');
        $titulo = trim((string) $this->request->getPost('titulo'));
        $slug = trim((string) $this->request->getPost('slug'));

        return [
            'titulo'       => $titulo,
            'slug'         => $slug !== '' ? url_title($slug, '-', true) : url_title($titulo, '-', true),
            'sinopsis'     => trim((string) $this->request->getPost('sinopsis')),
            'precio'       => $this->request->getPost('precio'),
            'stock'        => $this->request->getPost('stock'),
            'isbn'         => trim((string) $this->request->getPost('isbn')) ?: null,
            'portada'      => trim((string) $this->request->getPost('portada')) ?: null,
            'categoria_id' => $this->request->getPost('categoria_id'),
            'autor_id'     => $this->request->getPost('autor_id'),
            'destacado'    => $this->request->getPost('destacado') === '1' ? 1 : 0,
        ];
    }

    private function datosFormulario(string $titulo, array $libro = [], array $errores = [], ?int $id = null): array
    {
        return [
            'titulo'     => $titulo,
            'libro'      => $libro,
            'errores'    => $errores,
            'id'         => $id,
            'categorias' => model(CategoriaModel::class)->orderBy('nombre')->findAll(),
            'autores'    => model(AutorModel::class)->orderBy('nombre')->findAll(),
        ];
    }
}
