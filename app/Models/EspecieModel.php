<?php

namespace App\Models;

use App\Entities\EspecieEntity;
use CodeIgniter\Model;

class EspecieModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'especie';
  protected $primaryKey       = 'idEspecie';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = EspecieEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idEspecie",
    "nombre",
    "descripcion",
  ];

  // Validation
  protected $validationRules      = [
    "idEspecie" => "required|is_unique[propietario.idPropietario]",
    "nombre" => "required|max_length[30]",
  ];
  protected $validationMessages   = [
    "idEspecie" => [
      "required" => "El Id de Especie es obligatorio",
      "is_unique" => "El Id de Especie ingresado ya existe",
    ],
    "nombre" => [
      "required" => "El nombre es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (30)"
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
