<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PropietarioEntity extends Entity {
  protected $datamap = ["id" => "idPropietario"];
  protected $attributes = [
    "idPropietario" => null,
    "cedula" => null,
    "nombre" => null,
    "apellido" => null,
    "direccion" => null,
    "telefonoPrincipal" => null,
    "telefono2" => null,
    "estatus" => null,
  ];
}
