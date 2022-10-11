<?php

namespace App\Beans;

use App\Entities\RazaEntity;
use App\Traits\ArrayTrait;

class RazaBean {
  /**
   * @var int
   */
  public $idEspecie;
  /**
   * @var int
   */
  public $idRaza;
  /**
   * @var string
   */
  public $nombre;
  public $descripcion;
  /**
   * @var string
   */
  public $tamanio;

  public function __construct(RazaEntity $raza = null) {
    if ($raza) {
      $this->idEspecie = $raza->idEspecie;
      $this->idRaza = $raza->idRaza;
      $this->nombre = $raza->nombre;
      $this->descripcion = $raza->descripcion;
      $this->tamanio = $raza->tamanio;
    }
  }

  public function getRazaEntity(): RazaEntity {
    return new RazaEntity(array(
      "idEspecie" => $this->idEspecie,
      "idRaza" => $this->idRaza,
      "nombre" => $this->nombre,
      "descripcion" => $this->descripcion,
      "tamanio" => $this->tamanio,
    ));
  }

  public function setRazaEntity(RazaEntity $raza) {
    $this->idEspecie = $raza->idEspecie;
    $this->idRaza = $raza->idRaza;
    $this->nombre = $raza->nombre;
    $this->descripcion = $raza->descripcion;
    $this->tamanio = $raza->tamanio;
  }

  public static function arrayEntitiesToBeans(array $razas) {
    $beans = [];
    for ($i = 0; $i < count($razas); $i++) {
      $raza = $razas[$i];
      $rb = new EspecieBean($raza);
      $beans[$i] = $rb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): RazaBean {
    $rb = new RazaBean();
    $rb->idEspecie = $form["idEspecie"];
    $rb->idRaza = $form["idRaza"];
    $rb->nombre = $form["nombre"];
    $rb->descripcion = $form["descripcion"];
    $rb->tamanio = $form["tamanio"];
    return $rb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = ["nombre", "descripcion", "tamanio"];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
