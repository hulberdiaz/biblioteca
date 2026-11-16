<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('catalogo', 'Tienda::index');
$routes->get('libro/(:segment)', 'Tienda::detalle/$1');

$routes->get('carrito', 'Carrito::index');
$routes->post('carrito/agregar/(:num)', 'Carrito::agregar/$1');
$routes->post('carrito/actualizar/(:num)', 'Carrito::actualizar/$1');
$routes->post('carrito/eliminar/(:num)', 'Carrito::eliminar/$1');
$routes->get('checkout', 'Carrito::checkout');
$routes->post('checkout', 'Carrito::procesarCheckout');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::procesarLogin');
$routes->get('registro', 'Auth::registro');
$routes->post('registro', 'Auth::procesarRegistro');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('admin', ['filter' => ['auth', 'admin']], static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\Libros::index');

    $routes->get('libros', 'Admin\Libros::index');
    $routes->get('libros/nuevo', 'Admin\Libros::nuevo');
    $routes->post('libros', 'Admin\Libros::crear');
    $routes->get('libros/editar/(:num)', 'Admin\Libros::editar/$1');
    $routes->put('libros/(:num)', 'Admin\Libros::actualizar/$1');
    $routes->patch('libros/(:num)', 'Admin\Libros::actualizar/$1');
    $routes->delete('libros/(:num)', 'Admin\Libros::eliminar/$1');

    $routes->get('categorias', 'Admin\Categorias::index');
    $routes->get('categorias/nuevo', 'Admin\Categorias::nuevo');
    $routes->post('categorias', 'Admin\Categorias::crear');
    $routes->get('categorias/editar/(:num)', 'Admin\Categorias::editar/$1');
    $routes->put('categorias/(:num)', 'Admin\Categorias::actualizar/$1');
    $routes->patch('categorias/(:num)', 'Admin\Categorias::actualizar/$1');
    $routes->delete('categorias/(:num)', 'Admin\Categorias::eliminar/$1');

    $routes->get('autores', 'Admin\Autores::index');
    $routes->get('autores/nuevo', 'Admin\Autores::nuevo');
    $routes->post('autores', 'Admin\Autores::crear');
    $routes->get('autores/editar/(:num)', 'Admin\Autores::editar/$1');
    $routes->put('autores/(:num)', 'Admin\Autores::actualizar/$1');
    $routes->patch('autores/(:num)', 'Admin\Autores::actualizar/$1');
    $routes->delete('autores/(:num)', 'Admin\Autores::eliminar/$1');

    $routes->get('pedidos', 'Admin\Pedidos::index');
    $routes->get('pedidos/(:num)', 'Admin\Pedidos::detalle/$1');
});
