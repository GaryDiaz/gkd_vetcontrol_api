<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EspecieEntity extends Entity {
  protected $datamap = ["id" => "idEspecie"];
  protected $attributes   = [
    "idEspecie" => null,
    "nombre" => null,
    "descripcion" => null,
  ];
}
