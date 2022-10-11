<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PacienteEntity extends Entity {
  protected $datamap = ["id" => "idPaciente"];
  protected $attributes = [
    "idPaciente" => null,
    "idHistoriaClinica" => null,
    "idPropietario" => null,
    "nombre" => null,
    "idEspecie" => null,
    "idRaza" => null,
    "color" => null,
    "sexo" => null,
    "fechaNacimiento" => null,
    "SeniasParticulares" => null,
    "procedencia" => null,
    "estatus" => null,
  ];
}
