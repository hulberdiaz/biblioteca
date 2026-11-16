<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedidos';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id',
        'estado',
        'total',
        'direccion_envio',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'usuario_id'       => 'required|is_natural_no_zero',
        'estado'           => 'required|in_list[pendiente,pagado,enviado,completado,cancelado]',
        'total'            => 'required|decimal|greater_than_equal_to[0]',
        'direccion_envio'  => 'permit_empty|max_length[500]',
    ];

    public function getConUsuario(): self
    {
        $this->select('pedidos.*, usuarios.nombre AS usuario_nombre, usuarios.email AS usuario_email')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id');

        return $this;
    }

    public function getDetalle(int $pedidoId): ?array
    {
        return $this->db->table('pedidos')
            ->select('pedidos.*, usuarios.nombre AS usuario_nombre, usuarios.email AS usuario_email')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id')
            ->where('pedidos.id', $pedidoId)
            ->get()
            ->getRowArray();
    }
}
