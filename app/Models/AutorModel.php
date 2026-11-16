<?php

namespace App\Models;

use CodeIgniter\Model;

class AutorModel extends Model
{
    protected $table            = 'autores';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'biografia', 'foto'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nombre'    => 'required|max_length[160]',
        'biografia' => 'permit_empty',
        'foto'      => 'permit_empty|max_length[255]',
    ];
}
