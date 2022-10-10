<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class VeterinarioEntity extends Entity {
  protected $datamap = ["id" => "idVeterinario"];
  protected $attributes = [
    "idVeterinario" => null,
    "idUsuario" => null,
    "nombre" => null,
    "apellido" => null,
    "telefono" => null,
    "email" => null,
    "estatus" => null,
  ];
}
