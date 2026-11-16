<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'slug', 'descripcion'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nombre'      => 'required|max_length[120]',
        'slug'        => 'required|max_length[140]|alpha_dash|is_unique[categorias.slug,id,{id}]',
        'descripcion' => 'permit_empty',
    ];

    public function principales(int $limite = 8): array
    {
        return $this->orderBy('nombre', 'ASC')->findAll($limite);
    }
}
