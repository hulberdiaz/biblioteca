<?php

namespace App\Models;

use CodeIgniter\Model;

class LibroModel extends Model
{
    protected $table            = 'libros';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'titulo',
        'slug',
        'sinopsis',
        'precio',
        'stock',
        'isbn',
        'portada',
        'categoria_id',
        'autor_id',
        'destacado',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'titulo'       => 'required|max_length[200]',
        'slug'         => 'required|max_length[220]|alpha_dash|is_unique[libros.slug,id,{id}]',
        'sinopsis'     => 'permit_empty',
        'precio'       => 'required|decimal|greater_than_equal_to[0]',
        'stock'        => 'required|is_natural',
        'isbn'         => 'permit_empty|max_length[20]|is_unique[libros.isbn,id,{id}]',
        'portada'      => 'permit_empty|max_length[255]',
        'categoria_id' => 'required|is_natural_no_zero',
        'autor_id'     => 'required|is_natural_no_zero',
        'destacado'    => 'permit_empty|in_list[0,1]',
    ];

    public function withRelations(): self
    {
        $this->select(
            'libros.*, autores.nombre AS autor_nombre, categorias.nombre AS categoria_nombre'
        )
            ->join('autores', 'autores.id = libros.autor_id')
            ->join('categorias', 'categorias.id = libros.categoria_id');

        return $this;
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->withRelations()
            ->where('libros.slug', $slug)
            ->first();
    }

    public function getDestacados(int $limite = 8): array
    {
        return $this->withRelations()
            ->where('libros.destacado', 1)
            ->orderBy('libros.created_at', 'DESC')
            ->findAll($limite);
    }

    public function getNovedades(int $limite = 8): array
    {
        return $this->withRelations()
            ->orderBy('libros.created_at', 'DESC')
            ->findAll($limite);
    }
}
