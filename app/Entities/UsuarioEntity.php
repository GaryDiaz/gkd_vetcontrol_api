<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UsuarioEntity extends Entity {
  protected $datamap = ["id" => "idUsuario"];
  protected $attributes = [
    "idUsuario" => null,
    'nick' => null,
    'apiKey' => null,
    'clave' => null,
    'rol' => null,
    'nombre' => null,
    'apellido' => null,
    'estatus' => null,
    'ultimoAcceso' => null,
  ];
}
