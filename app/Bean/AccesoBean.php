<?php

namespace App\Beans;

use App\Entities\UsuarioEntity;
use App\Models\UsuarioModel;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccesoBean extends UsuarioBean {
  private $apiKey;
  private $clave;
  private $ultimoAcceso;

  public function __construct(UsuarioEntity $usuario = null) {
    parent::__construct($usuario);
    if ($usuario) {
      $this->apiKey = $usuario->apiKey;
      $this->clave = $usuario->clave;
      $this->ultimoAcceso = $usuario->ultimoAcceso;
    }
  }

  public function getUsuarioEntity(): UsuarioEntity {
    $data = new UsuarioEntity(array(
      "idUsuario" => $this->id,
      'nick' => $this->nick,
      'clave' => $this->clave,
      'apiKey' => $this->apiKey,
      'rol' => $this->rol,
      'nombre' => $this->nombre,
      'apellido' => $this->apellido,
      'estatus' => $this->estatus,
      'ultimoAcceso' => $this->ultimoAcceso,
    ));
    return $data;
  }

  public function setUsuarioEntity(UsuarioEntity $usuario) {
    parent::setUsuarioEntity($usuario);
    $this->apiKey = $usuario->apiKey;
    $this->clave = $usuario->clave;
    $this->ultimoAcceso = $usuario->ultimoAcceso;
  }

  public function generarToken(): string {
    $key = getenv("JWT_SECRET");
    $payload = array(
      "nick" => $this->nick,
      "apiKey" => $this->apiKey,
      "rol" => $this->rol,
      "nombre" => $this->getNombreCompleto(),
      "iat" => $this->ultimoAcceso,
    );

    return JWT::encode($payload, $key, "HS256");
  }

  public static function decodificarToken($jwt) {
    try {
      if (!$jwt) {
        throw new Exception("Se requiere iniciar sesión");
      }
      $key = getenv("JWT_SECRET");
      $payload = JWT::decode($jwt, new Key($key, "HS256"));
      return $payload;
    } catch (\Throwable $e) {
      throw new Exception("Se requiere iniciar");
    }
  }

  public static function validarToken($payload) {
    try {
      $nick = $payload->nick;
      $model = new UsuarioModel();
      $usuario = $model->findByNick($nick);
      if (!$usuario) {
        throw new Exception("No posee un token válido (no se reconoce el usuario), se requiere iniciar sesión");
      }
      if ($usuario["apiKey"] !== $payload->apiKey) {
        throw new Exception("No posee un token válido o su token ha expirado, se requiere iniciar sesión");
      }
      return $usuario;
    } catch (\Throwable $e) {
      throw new Exception("No posee un token válido, se requiere iniciar sesión");
    }
  }

  public static function getInstanceDataArray(array $data): AccesoBean {
    $ab = new AccesoBean();
    $ab->id = 0;
    $ab->nick = $data["nick"];
    $ab->rol = $data["rol"];
    $ab->nombre = $data["nombre"];
    $ab->apellido = $data["apellido"];
    $ab->estatus = $data["ACTIVO"];
    $ab->apiKey = $data["apiKey"];
    $ab->clave = $data["clave"];
    $ab->ultimoAcceso = $data["ultimoAcceso"];
    return $ab;
  }
}
