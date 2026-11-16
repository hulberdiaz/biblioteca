<?php

namespace App\Models;

use CodeIgniter\Model;

class DetallePedidoModel extends Model
{
    protected $table            = 'detalle_pedidos';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pedido_id',
        'libro_id',
        'titulo_libro',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];
    protected $useTimestamps = false;

    protected $validationRules = [
        'pedido_id'       => 'required|is_natural_no_zero',
        'libro_id'        => 'required|is_natural_no_zero',
        'titulo_libro'    => 'required|max_length[200]',
        'precio_unitario' => 'required|decimal|greater_than_equal_to[0]',
        'cantidad'        => 'required|is_natural_no_zero',
        'subtotal'        => 'required|decimal|greater_than_equal_to[0]',
    ];
}
