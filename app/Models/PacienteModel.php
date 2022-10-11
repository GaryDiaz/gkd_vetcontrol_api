<?php

namespace App\Models;

use App\Entities\PacienteEntity;
use CodeIgniter\Model;

class PacienteModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'paciente';
  protected $primaryKey       = 'idPaciente';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = PacienteEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idPaciente",
    "idHistoriaClinica",
    "idPropietario",
    "nombre",
    "idEspecie",
    "idRaza",
    "color",
    "sexo",
    "fechaNacimiento",
    "SeniasParticulares",
    "procedencia",
    "estatus",
  ];

  // Validation
  protected $validationRules      = [
    "idPaciente" => "required|is_unique[paciente.idPaciente]",
    "idHistoriaClinica" => "required|is_unique[paciente.idHistoriaClinica]",
    "idPropietario" => "required|is_not_unique[propietario.idPropietario]",
    "nombre" => "required|max_length[20]",
    "idEspecie" => "required|is_not_unique[especie.idEspecie]",
    "idRaza" => "required|is_not_unique[raza.idRaza]",
    "color" => "required|max_length[30]",
    "sexo" => "required|in_list[M,F]",
    "fechaNacimiento" => "required|valid_date[Y-m-d]",
    "procedencia" => "required|in_list[RURAL,URBANA,OTRA]",
    "estatus" => "required|in_list[ACTIVO,INACTIVO]",
  ];
  protected $validationMessages   = [
    "idPaciente" => [
      "required" => "El Id de Paciente es obligatorio",
      "is_unique" => "El Id de Paciente ya existe"
    ],
    "idHistoriaClinica" => [
      "required" => "El Id de Historia Clínica es obligatorio",
      "is_unique" => "El Id de Historia Clínica ya existe"
    ],
    "idPropietario" => [
      "required" => "El Id de Propietario es obligatorio",
      "is_not_unique" => "El Id de Propietario no existe"
    ],
    "nombre" => [
      "required" => "El Nombre es obligatorio",
      "max_length" => "Ha excedido de la longitud máxima(20)"
    ],
    "idEspecie" => [
      "required" => "El Id de Especie es obligatorio",
      "is_not_unique" => "El Id de Especie no existe"
    ],
    "idRaza" => [
      "required" => "El Id de Raza es obligatorio",
      "is_not_unique" => "El Id de Raza no existe"
    ],
    "color" => [
      "required" => "El Color es obligatorio",
      "max_length" => "Ha excedido de la longitud máxima(30)"
    ],
    "sexo" => [
      "required" => "El Sexo es obligatorio",
      "in_list" => "Debe seleccionar el sexo"
    ],
    "fechaNacimiento" => [
      "required" => "La Fecha de Nacimiento es obligatorio, puede ser aproximada",
      "valid_date" => ""
    ],
    "procedencia" => [
      "required" => "La Procedencia es obligatorio",
      "in_list" => "Debe seleccionar la procedencia",
    ],
    "estatus" => [
      "required" => "El Estatus es obligatorio",
      "in_list" => "Debe seleccionar el estatus",
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
