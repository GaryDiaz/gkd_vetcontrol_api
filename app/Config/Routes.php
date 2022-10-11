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

  $routes->post("/especie", "EspecieController::create");
  $routes->get("/especies", "EspecieController::index");
  $routes->get("/especie/(:segment)", "EspecieController::show/$1");
  $routes->put("/especie/(:segment)", "EspecieController::update/$1");
  $routes->delete("/especie/(:segment)", "EspecieController::delete/$1");

  $routes->post("/propietario", "PropietarioController::create");
  $routes->get("/propietarios", "PropietarioController::index");
  $routes->get("/propietario/(:segment)", "PropietarioController::show/$1");
  $routes->put("/propietario/(:segment)", "PropietarioController::update/$1");
  $routes->delete("/propietario/(:segment)", "PropietarioController::delete/$1");

  $routes->post("/raza", "PropietarioController::create");
  $routes->get("/razas/(:segment)", "PropietarioController::list/$1");
  $routes->get("/raza/(:segment)", "PropietarioController::show/$1");
  $routes->put("/raza/(:segment)", "PropietarioController::update/$1");
  $routes->delete("/raza/(:segment)", "PropietarioController::delete/$1");

  $routes->post("/usuario", "UsuarioController::create");
  $routes->get("/usuarios", "UsuarioController::index");
  $routes->get("/usuario/(:segment)", "UsuarioController::show/$1");
  $routes->put("/usuario/(:segment)", "UsuarioController::update/$1");
  $routes->delete("/usuario/(:segment)", "UsuarioController::update/$1");
  $routes->post("/cambiarclave", "UsuarioController::cambiarClave");

  $routes->post("/veterinario", "VeterinarioController::create");
  $routes->get("/veterinarios", "VeterinarioController::index");
  $routes->get("/veterinario/(:segment)", "VeterinarioController::show/$1");
  $routes->put("/veterinario/(:segment)", "VeterinarioController::update/$1");
  $routes->delete("/veterinario/(:segment)", "VeterinarioController::delete/$1");
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
