<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
  require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->options(".*", "*", ['filter' => 'cors']);
$routes->post("login", "UsuarioController::login", ['filter' => 'cors']);

/**
 * Rutas protegidas por el filtro de acceso y Cors
 */

$routes->group("", ["filter" => ["cors", "acceso"]], static function ($routes) {
  $routes->post("/usuario", "UsuarioController::create");
  $routes->get("/usuarios", "UsuarioController::index");
  $routes->get("/usuario/(:segment)", "UsuarioController::show/$1");
  $routes->put("/usuario/(:segment)", "UsuarioController::update/$1");
  $routes->delete("/usuario/(:segment)", "UsuarioController::update/$1");
  $routes->post("/cambiarclave", "UsuarioController::cambiarClave");
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
  require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
