<?php

namespace App\Beans;

use App\Entities\PropietarioEntity;
use App\Traits\ArrayTrait;

class PropietarioBean {
  /**
   * @var int
   */
  public $id;
  /**
   * @var int
   */
  public $cedula;
  /**
   * @var string
   */
  public $nombre;
  /**
   * @var string
   */
  public $apellido;
  public $direccion;
  /**
   * @var string
   */
  public $telefonoPrincipal;
  public $telefono2;
  /**
   * @var string
   */
  public $email;
  /**
   * @var string
   */
  public $estatus;

  public function __construct(PropietarioEntity $propietario = null) {
    if ($propietario) {
      $this->id = $propietario->id;
      $this->cedula = $propietario->cedula;
      $this->nombre = $propietario->nombre;
      $this->apellido = $propietario->apellido;
      $this->direccion = $propietario->direccion;
      $this->telefonoPrincipal = $propietario->telefonoPrincipal;
      $this->telefono2 = $propietario->telefono2;
      $this->estatus = $propietario->estatus;
    }
  }

  public function getNombreCompleto(): string {
    return $this->nombre . " " . $this->apellido;
  }

  public function getPropietarioEntity(): PropietarioEntity {
    return new PropietarioEntity(array(
      "idPropietario" => $this->id,
      "cedula" => $this->cedula,
      "nombre" => $this->nombre,
      "apellido" => $this->apellido,
      "direccion" => $this->direccion,
      "telefonoPrincipal" => $this->telefonoPrincipal,
      "telefono2" => $this->telefono2,
      "estatus" => $this->estatus,
    ));
  }

  public function setPropietarioEntity(PropietarioEntity $propietario) {
    $this->id = $propietario->id;
    $this->cedula = $propietario->cedula;
    $this->nombre = $propietario->nombre;
    $this->apellido = $propietario->apellido;
    $this->direccion = $propietario->direccion;
    $this->telefonoPrincipal = $propietario->telefonoPrincipal;
    $this->telefono2 = $propietario->telefono2;
    $this->estatus = $propietario->estatus;
  }

  public static function arrayEntitiesToBeans(array $propietarios): array {
    $beans = [];
    for ($i = 0; $i < count($propietarios); $i++) {
      $propietario = $propietarios[$i];
      $pb = new PropietarioBean($propietario);
      $beans[$i] = $pb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): PropietarioBean {
    $pb = new PropietarioBean();
    $pb->id = 0;
    $pb->cedula = $form["cedula"];
    $pb->nombre = $form["nombre"];
    $pb->apellido = $form["apellido"];
    $pb->direccion = $form["direccion"];
    $pb->telefonoPrincipal = $form["telefonoPrincipal"];
    $pb->telefono2 = $form["telefono2"];
    $pb->estatus = $form["estatus"];
    return $pb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = ["nombre", "apellido", "direccion", "telefonoPrincipal", "telefono2"];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
