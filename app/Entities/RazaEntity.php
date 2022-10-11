<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RazaEntity extends Entity {
  protected $attributes   = [
    "idRaza" => null,
    "idEspecie" => null,
    "nombre" => null,
    "descripcion" => null,
    "tamanio" => null,
  ];
}
