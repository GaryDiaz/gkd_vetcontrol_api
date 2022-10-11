<?php

namespace App\Beans;

use App\Entities\EspecieEntity;
use App\Traits\ArrayTrait;

class EspecieBean {
  /**
   * @var int
   */
  public $id;
  /**
   * @var string
   */
  public $nombre;
  public $descripcion;

  public function __construct(EspecieEntity $especie = null) {
    if ($especie) {
      $this->id = $especie->id;
      $this->nombre = $especie->nombre;
      $this->descripcion = $especie->descripcion;
    }
  }

  public function getEspecieEntity(): EspecieEntity {
    return new EspecieEntity(array(
      "id" => $this->id,
      "nombre" => $this->nombre,
      "descripcion" => $this->descripcion,
    ));
  }

  public function setEspecieEntity(EspecieEntity $especie) {
    $this->id = $especie->id;
    $this->nombre = $especie->nombre;
    $this->descripcion = $especie->descripcion;
  }

  public static function arrayEntitiesToBeans(array $especies) {
    $beans = [];
    for ($i = 0; $i < count($especies); $i++) {
      $especie = $especies[$i];
      $eb = new EspecieBean($especie);
      $beans[$i] = $eb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): EspecieBean {
    $eb = new EspecieBean();
    $eb->id = 0;
    $eb->nombre = $form["nombre"];
    $eb->descripcion = $form["descripcion"];
    return $eb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = ["nombre", "descripcion"];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
