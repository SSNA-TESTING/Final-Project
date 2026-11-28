<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    foreach (['products' => 'Products', 'customers' => 'Customers', 'staff' => 'Staff'] as $path => $ctl) {
        $routes->get($path, "$ctl::index");
        $routes->get("$path/create", "$ctl::create");
        $routes->post("$path/store", "$ctl::store");
        $routes->get("$path/edit/(:num)", "$ctl::edit/$1");
        $routes->post("$path/update/(:num)", "$ctl::update/$1");
        $routes->post("$path/delete/(:num)", "$ctl::delete/$1");
    }

    $routes->get('sales/create', 'Sales::create');
    $routes->post('sales/store', 'Sales::store');
    $routes->get('sales/history', 'Sales::history');
});
