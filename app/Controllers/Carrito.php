<?php

namespace App\Controllers;

use App\Models\LibroModel;

class Carrito extends BaseController
{
    public function index(): string
    {
        $carrito  = $this->carritoActual();
        $productos = [];

        if ($carrito !== []) {
            $productos = model(LibroModel::class)
                ->withRelations()
                ->whereIn('libros.id', array_keys($carrito))
                ->findAll();
        }

        $lineas = [];
        $total  = 0.0;

        foreach ($productos as $producto) {
            $cantidad = $carrito[(int) $producto['id']] ?? 0;
            if ($cantidad < 1) {
                continue;
            }

            $producto['cantidad'] = $cantidad;
            $producto['subtotal'] = round((float) $producto['precio'] * $cantidad, 2);
            $total += $producto['subtotal'];
            $lineas[] = $producto;
        }

        return view('tienda/carrito', [
            'titulo'    => 'Tu carrito',
            'lineas'    => $lineas,
            'total'     => round($total, 2),
            'carrito'   => $carrito,
        ]);
    }

    public function agregar(int $id)
    {
        $libro = model(LibroModel::class)->find($id);
        $cantidad = filter_var($this->request->getPost('cantidad'), FILTER_VALIDATE_INT);

        if ($libro === null || $cantidad === false || $cantidad < 1) {
            return redirect()->back()->with('error', 'No se pudo agregar ese libro al carrito.');
        }

        if ((int) $libro['stock'] < 1) {
            return redirect()->back()->with('error', 'Este libro no tiene unidades disponibles.');
        }

        $carrito = $this->carritoActual();
        $nuevoTotal = ($carrito[$id] ?? 0) + $cantidad;

        if ($nuevoTotal > (int) $libro['stock']) {
            return redirect()->back()->with('error', 'La cantidad solicitada supera el inventario disponible.');
        }

        $carrito[$id] = $nuevoTotal;
        session()->set('carrito', $carrito);

        return redirect()->to(site_url('carrito'))->with('success', 'Libro agregado al carrito.');
    }

    public function actualizar(int $id)
    {
        $cantidad = filter_var($this->request->getPost('cantidad'), FILTER_VALIDATE_INT);
        $libro    = model(LibroModel::class)->find($id);
        $carrito  = $this->carritoActual();

        if (! isset($carrito[$id]) || $libro === null || $cantidad === false || $cantidad < 1) {
            return redirect()->to(site_url('carrito'))->with('error', 'La cantidad indicada no es válida.');
        }

        if ($cantidad > (int) $libro['stock']) {
            return redirect()->to(site_url('carrito'))->with('error', 'No hay suficientes unidades disponibles.');
        }

        $carrito[$id] = $cantidad;
        session()->set('carrito', $carrito);

        return redirect()->to(site_url('carrito'))->with('success', 'Carrito actualizado.');
    }

    public function eliminar(int $id)
    {
        $carrito = $this->carritoActual();
        unset($carrito[$id]);
        session()->set('carrito', $carrito);

        return redirect()->to(site_url('carrito'))->with('success', 'Libro eliminado del carrito.');
    }

    public function checkout(): string
    {
        if (session()->get('user_id') === null) {
            return redirect()->to(site_url('login'))->with('error', 'Inicia sesión para finalizar la compra.');
        }

        if ($this->carritoActual() === []) {
            return redirect()->to(site_url('carrito'))->with('error', 'Tu carrito está vacío.');
        }

        $carrito = $this->carritoActual();
        $productos = model(LibroModel::class)
            ->withRelations()
            ->whereIn('libros.id', array_keys($carrito))
            ->findAll();

        return view('tienda/checkout', [
            'titulo'    => 'Finalizar compra',
            'productos' => $this->prepararLineas($productos, $carrito),
        ]);
    }

    public function procesarCheckout()
    {
        if (session()->get('user_id') === null) {
            return redirect()->to(site_url('login'))->with('error', 'Inicia sesión para finalizar la compra.');
        }

        if (! $this->validate(['direccion_envio' => 'required|max_length[500]'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $carrito = $this->carritoActual();
        if ($carrito === []) {
            return redirect()->to(site_url('carrito'))->with('error', 'Tu carrito está vacío.');
        }

        $db = db_connect();
        if (! $db->transBegin()) {
            return redirect()->to(site_url('carrito'))->with('error', 'No se pudo iniciar la compra. Inténtalo nuevamente.');
        }

        $lineas = [];
        $total  = 0.0;
        $ahora  = date('Y-m-d H:i:s');

        foreach ($carrito as $libroId => $cantidad) {
            $consulta = $db->query(
                'SELECT id, titulo, precio, stock FROM libros WHERE id = ? FOR UPDATE',
                [(int) $libroId],
            );
            $libro = $consulta->getRowArray();

            if ($libro === null || $cantidad > (int) $libro['stock']) {
                $db->transRollback();
                return redirect()->to(site_url('carrito'))->with('error', 'El inventario cambió. Revisa las cantidades del carrito.');
            }

            $precio   = (float) $libro['precio'];
            $subtotal = round($precio * $cantidad, 2);
            $total   += $subtotal;
            $lineas[] = [
                'libro_id'        => (int) $libro['id'],
                'titulo_libro'    => $libro['titulo'],
                'precio_unitario' => number_format($precio, 2, '.', ''),
                'cantidad'        => $cantidad,
                'subtotal'        => number_format($subtotal, 2, '.', ''),
                'stock_restante'  => (int) $libro['stock'] - $cantidad,
            ];
        }

        $db->table('pedidos')->insert([
            'usuario_id'       => (int) session()->get('user_id'),
            'estado'           => 'pendiente',
            'total'            => number_format($total, 2, '.', ''),
            'direccion_envio'  => trim((string) $this->request->getPost('direccion_envio')),
            'created_at'       => $ahora,
            'updated_at'       => $ahora,
        ]);

        if (! $db->transStatus()) {
            $db->transRollback();
            log_message('error', 'No se pudo crear el pedido: ' . $db->error()['message']);
            return redirect()->to(site_url('carrito'))->with('error', 'No se pudo guardar el pedido. Inténtalo nuevamente.');
        }

        $pedidoId = (int) $db->insertID();

        foreach ($lineas as $linea) {
            $db->table('detalle_pedidos')->insert([
                'pedido_id'       => $pedidoId,
                'libro_id'        => $linea['libro_id'],
                'titulo_libro'    => $linea['titulo_libro'],
                'precio_unitario' => $linea['precio_unitario'],
                'cantidad'        => $linea['cantidad'],
                'subtotal'        => $linea['subtotal'],
            ]);
            $db->table('libros')
                ->where('id', $linea['libro_id'])
                ->update(['stock' => $linea['stock_restante'], 'updated_at' => $ahora]);
        }

        if (! $db->transStatus()) {
            $db->transRollback();
            log_message('error', 'Falló el registro de los detalles del pedido ' . $pedidoId . ': ' . $db->error()['message']);
            return redirect()->to(site_url('carrito'))->with('error', 'No se pudo completar el pedido. No se descontó el inventario.');
        }

        if (! $db->transCommit()) {
            log_message('error', 'No se pudo confirmar la transacción del pedido ' . $pedidoId . ': ' . $db->error()['message']);
            return redirect()->to(site_url('carrito'))->with('error', 'No se pudo confirmar el pedido. Inténtalo nuevamente.');
        }

        session()->remove('carrito');

        return redirect()->to(site_url())->with('success', 'Pedido #' . $pedidoId . ' recibido. Te contactaremos para coordinar el pago.');
    }

    private function carritoActual(): array
    {
        $carrito = session()->get('carrito');
        if (! is_array($carrito)) {
            return [];
        }

        $limpio = [];
        foreach ($carrito as $id => $cantidad) {
            if (ctype_digit((string) $id) && (int) $id > 0 && is_int($cantidad) && $cantidad > 0) {
                $limpio[(int) $id] = $cantidad;
            }
        }

        return $limpio;
    }

    private function prepararLineas(array $productos, array $carrito): array
    {
        foreach ($productos as &$producto) {
            $producto['cantidad'] = $carrito[(int) $producto['id']] ?? 0;
            $producto['subtotal'] = round((float) $producto['precio'] * $producto['cantidad'], 2);
        }
        unset($producto);

        return $productos;
    }
}
