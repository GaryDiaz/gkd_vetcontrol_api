<?php

namespace App\Beans;

use App\Entities\UsuarioEntity;

class UsuarioBean {
  /**
   * @var int
   */
  public $id;
  /**
   * @var string
   */
  public $nick;
  /**
   * @var string
   */
  public $rol;
  /**
   * @var string
   */
  public $nombre;
  /**
   * @var string
   */
  public $apellido;
  /**
   * @var string
   */
  public $estatus;

  public function __construct(UsuarioEntity $usuario = null) {
    if ($usuario) {
      $this->id = $usuario->id;
      $this->nick = $usuario->nick;
      $this->rol = $usuario->rol;
      $this->nombre = $usuario->nombre;
      $this->apellido = $usuario->apellido;
      $this->estatus = $usuario->estatus;
    }
  }

  public function getNombreCompleto(): string {
    return $this->nombre . " " . $this->apellido;
  }

  public function getUsuarioEntity(): UsuarioEntity {
    $data = new UsuarioEntity(array(
      "idUsuario" => $this->id,
      'nick' => $this->nick,
      'rol' => $this->rol,
      'nombre' => $this->nombre,
      'apellido' => $this->apellido,
      'estatus' => $this->estatus,
    ));
    return $data;
  }

  public function setUsuarioEntity(UsuarioEntity $usuario) {
    $this->id = intval($usuario->id);
    $this->nick = $usuario->nick;
    $this->rol = $usuario->rol;
    $this->nombre = $usuario->nombre;
    $this->apellido = $usuario->apellido;
    $this->estatus = $usuario->estatus;
  }

  public static function arrayEntitiesToBeans(array $usuarios): array {
    $beans = [];
    for ($i = 0; $i < count($usuarios); $i++) {
      $usuario = $usuarios[$i];
      $ub = new UsuarioBean($usuario);
      $beans[$i] = $ub;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): UsuarioBean {
    $ub = new UsuarioBean();
    $ub->id = 0;
    $ub->nick = $form["nick"];
    $ub->rol = $form["rol"];
    $ub->nombre = $form["nombre"];
    $ub->apellido = $form["apellido"];
    $ub->estatus = $form["ACTIVO"];
    return $ub;
  }
}
