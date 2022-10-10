<?php

namespace App\Models;

use App\Entities\PropietarioEntity;
use CodeIgniter\Model;

class PropietarioModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'propietario';
  protected $primaryKey       = 'idPropietario';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = PropietarioEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idPropietario",
    "cedula",
    "nombre",
    "apellido",
    "direccion",
    "telefonoPrincipal",
    "telefono2",
    "estatus",
  ];

  // Validation
  protected $validationRules      = [
    "idPropietario" => "required|is_unique[propietario.idPropietario]",
    "cedula" => "required|is_unique[propietario.cedula]",
    "nombre" => "required|max_length[20]",
    "apellido" => "required|max_length[20]",
    "telefonoPrincipal" => "required|max_length[14]",
    "estatus" => "required|in_list[ACTIVO,INACTIVO]",
  ];
  protected $validationMessages   = [
    "idPropietario" => [
      "required" => "El Id de Propietario es obligatorio",
      "is_unique" => "El Id de Propietario ingresado ya existe",
    ],
    "cedula" => [
      "required" => "La cédula es obligatoria",
      "is_unique" => "La Cédula ingresada ya existe",
    ],
    "nombre" => [
      "required" => "El nombre es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (20)"
    ],
    "apellido" => [
      "required" => "El apellido es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (20)"
    ],
    "telefonoPrincipal" => [
      "required" => "El teléfono es obligatorio es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (255)"
    ],
    "estatus" => [
      "required" => "El estatus es obligatorio",
      "in_list" => "Debe seleccionar un estatus"
    ],
  ];
  protected $skipValidation       = false;

  public function nextId(): int {
    $builder = $this->db->table($this->table);
    $row = $builder->select($this->primaryKey)->orderBy($this->primaryKey, "DESC")
      ->limit(1)->get()->getRowArray();
    return $row ? $row[$this->primaryKey] + 1 : 1;
  }
}
