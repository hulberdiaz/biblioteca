<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AutorModel;
use App\Models\LibroModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Autores extends BaseController
{
    public function index(): string
    {
        return view('admin/autores/index', [
            'titulo'  => 'Administrar autores',
            'autores' => model(AutorModel::class)->orderBy('nombre')->findAll(),
        ]);
    }

    public function nuevo(): string
    {
        return view('admin/autores/form', ['titulo' => 'Nuevo autor', 'autor' => [], 'errores' => [], 'id' => null]);
    }

    public function crear()
    {
        $modelo = model(AutorModel::class);
        $datos  = $this->datosAutor();
        if (! $modelo->insert($datos)) {
            return view('admin/autores/form', ['titulo' => 'Nuevo autor', 'autor' => $datos, 'errores' => $modelo->errors(), 'id' => null]);
        }

        return redirect()->to(site_url('admin/autores'))->with('success', 'Autor creado.');
    }

    public function editar(int $id): string
    {
        $autor = model(AutorModel::class)->find($id);
        if ($autor === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el autor.');
        }

        return view('admin/autores/form', ['titulo' => 'Editar autor', 'autor' => $autor, 'errores' => [], 'id' => $id]);
    }

    public function actualizar(int $id)
    {
        $modelo = model(AutorModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el autor.');
        }

        $datos = $this->datosAutor();
        if (! $modelo->update($id, $datos)) {
            return view('admin/autores/form', ['titulo' => 'Editar autor', 'autor' => $datos, 'errores' => $modelo->errors(), 'id' => $id]);
        }

        return redirect()->to(site_url('admin/autores'))->with('success', 'Autor actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo = model(AutorModel::class);
        if ($modelo->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el autor.');
        }

        if (model(LibroModel::class)->where('autor_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/autores'))->with('error', 'No se puede borrar un autor que tiene libros asociados.');
        }

        if (! $modelo->delete($id)) {
            return redirect()->to(site_url('admin/autores'))->with('error', 'No se pudo eliminar el autor.');
        }

        return redirect()->to(site_url('admin/autores'))->with('success', 'Autor eliminado.');
    }

    private function datosAutor(): array
    {
        return [
            'nombre'    => trim((string) $this->request->getPost('nombre')),
            'biografia' => trim((string) $this->request->getPost('biografia')),
            'foto'      => trim((string) $this->request->getPost('foto')) ?: null,
        ];
    }
}
