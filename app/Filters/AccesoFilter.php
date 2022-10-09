<?php

namespace App\Filters;

use App\Beans\AccesoBean;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class AccesoFilter implements FilterInterface {
  /**
   * @param RequestInterface $request
   * @param array|null       $arguments
   *
   * @return mixed
   */
  public function before(RequestInterface $request, $arguments = null) {
    try {
      $token = $this->getToken();
      $payload = AccesoBean::decodificarToken($token);
      $usuario = AccesoBean::validarToken($payload);
      $_REQUEST["usuario"] = $usuario;
    } catch (Exception $exc) {
      $response = service("response");
      $response->setContentType("application/json");
      $response->setJSON([
        "error" => 401,
        "messages" => ["error" => $exc->getMessage()]
      ]);
      $response->setStatusCode(401);
      return $response;
    }
  }

  private function getToken(string $key = "GKD-Token"): string {
    $headers = apache_request_headers();
    if (!array_key_exists($key, $headers)) {
      return new Exception("No posee un token, debe iniciar sesión");
    }
    return $headers[$key];
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
