<?php

namespace App\Models;

use App\Entities\VeterinarioEntity;
use CodeIgniter\Model;

class VeterinarioModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'veterinario';
  protected $primaryKey       = 'idVeterinario';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = VeterinarioEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idVeterinario",
    "idUsuario",
    "nombre",
    "apellido",
    "telefono",
    "email",
    "estatus",
  ];

  // Validation
  protected $validationRules      = [
    "idVeterinario" => "required|is_unique[veterinario.idVeterinario]",
    "idUsuario" => "required|is_not_unique[usuario.idUsuario]",
    "nombre" => "required|max_length[20]",
    "apellido" => "required|max_length[20]",
    "telefono" => "required|max_length[14]",
    "email" => "required|valid_email|max_length[100]",
    "estatus" => "required|in_list[ACTIVO,INACTIVO]",
  ];
  protected $validationMessages   = [
    "idVeterinario" => [
      "required" => "El Id de Veterinario es obligatorio",
      "is_unique" => "El Id de Veterinario ingresado ya existe",
    ],
    "idUsuario" => [
      "required" => "El Id de Usuario es obligatorio",
      "is_not_unique" => "No existe ningún usuario con ese Id",
    ],
    "nombre" => [
      "required" => "El nombre es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (20)"
    ],
    "apellido" => [
      "required" => "El apellido es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (20)"
    ],
    "telefono" => [
      "required" => "El número de teléfono es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (14)"
    ],
    "email" => [
      "required" => "El email es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (100)",
      "valid_email" => "Debe ingresar un email válido",
    ],
    "estatus" => [
      "required" => "El estatus es obligatorio",
      "in_list" => "Debe seleccionar un estatus"
    ],
  ];
  protected $skipValidation       = false;

  public function nextId(): int {
    $builder = $this->db->table($this->table);
    $row = $builder->select("idVeterinario")->orderBy("idVeterinario", "DESC")
      ->limit(1)->get()->getRowArray();
    return $row ? $row["idVeterinario"] + 1 : 1;
  }
}
