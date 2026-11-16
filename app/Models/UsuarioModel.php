<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'email', 'password', 'rol'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nombre'   => 'required|max_length[160]',
        'email'    => 'required|valid_email|max_length[190]|is_unique[usuarios.email,id,{id}]',
        'password' => 'required|min_length[8]|max_length[255]',
        'rol'      => 'required|in_list[admin,cliente]',
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
