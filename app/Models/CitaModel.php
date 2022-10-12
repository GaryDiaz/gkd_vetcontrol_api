<?php

namespace App\Models;

use CodeIgniter\Model;

class CitaModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'cita';
  protected $primaryKey       = 'idCita';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idCita",
    "idPaciente",
    "idVeterinario",
    "fecha",
    "motivo",
    "estatus",
  ];

  // Validation
  protected $validationRules      = [
    "idCita" => "required|is_unique[cita.idCita]",
    "idPaciente" => "required|is_not_unique[paciente.idPaciente]",
    "idVeterinario" => "required|is_not_unique[veterinario.idVeterinario]",
    "fecha" => "required|valid_date[Y-m-d]",
    "motivo" => "required|max_length[255]",
    "estatus" => "required|in_list[ACTIVO,INACTIVO]",
  ];
  protected $validationMessages   = [
    "idCita" => [
      "required" => "El Id de Cita es obligatorio",
      "is_unique" => "El id de cita ya existe",
    ],
    "idPaciente" => [
      "required" => "El Id de Paciente es obligatorio",
      "is_not_unique" => "El Id de Paciente no existe",
    ],
    "idVeterinario" => [
      "required" => "El Id de Veterinario es obligatorio",
      "is_not_unique" => "El Id de Veterinario no existe",
    ],
    "fecha" => [
      "required" => "La fecha es obligatoria",
      "valid_date" => "Debe ingresar una fecha válida",
    ],
    "motivo" => [
      "required" => "El motivo es obligatorio",
      "max_length" => "Ha excedido de la longitud máxima(255)",
    ],
    "estatus" => [
      "required" => "El estatus es obligatorio",
      "in_list" => "Debe seleccionar un estatus",
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
