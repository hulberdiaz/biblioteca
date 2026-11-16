<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetallePedidoModel;
use App\Models\PedidoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pedidos extends BaseController
{
    public function index(): string
    {
        $modelo = model(PedidoModel::class);
        $pedidos = $modelo->getConUsuario()->orderBy('pedidos.created_at', 'DESC')->paginate(20);

        return view('admin/pedidos/index', [
            'titulo'  => 'Pedidos',
            'pedidos' => $pedidos,
            'pager'   => $modelo->pager,
        ]);
    }

    public function detalle(int $id): string
    {
        $pedido = model(PedidoModel::class)->getDetalle($id);
        if ($pedido === null) {
            throw PageNotFoundException::forPageNotFound('No se encontró el pedido.');
        }

        return view('admin/pedidos/detalle', [
            'titulo'  => 'Pedido #' . $id,
            'pedido'  => $pedido,
            'detalles' => model(DetallePedidoModel::class)->where('pedido_id', $id)->findAll(),
        ]);
    }
}
