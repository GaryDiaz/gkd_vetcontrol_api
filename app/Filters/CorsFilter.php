<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface {
  /**
   * @param RequestInterface $request
   * @param array|null       $arguments
   *
   * @return mixed
   */
  public function before(RequestInterface $request, $arguments = null) {
    header('Access-Control-Allow-Origin: *');
    //header("Access-Control-Allow-Headers: GKD-Token, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
    header('Access-Control-Allow-Headers: GKD-Token, Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
      $response = service("response");
      $response->setContentType("application/json");
      $response->setJSON(["message" => "Ok"]);
      $response->setStatusCode(200);
      return $response;
    }
  }

  /**
   * @param RequestInterface  $request
   * @param ResponseInterface $response
   * @param array|null        $arguments
   *
   * @return mixed
   */
  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    //
  }
}
