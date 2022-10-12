<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CitaEntity extends Entity {
  protected $datamap = ["id" => "idCita"];
  protected $attributes   = [
    "idCita" => null,
    "idPaciente" => null,
    "idVeterinario" => null,
    "fecha" => null,
    "motivo" => null,
    "estatus" => null,
  ];
}
