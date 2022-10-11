<?php

namespace App\Models;

use App\Entities\RazaEntity;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Model;
use ReflectionClass;
use ReflectionProperty;

class RazaModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'raza';
  protected $primaryKey       = ["idEspecie", "idRaza"];
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = RazaEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    "idRaza",
    "idEspecie",
    "nombre",
    "descripcion",
    "tamanio",
  ];

  // Validation
  protected $validationRules      = [
    "idRaza" => "required|is_natural",
    "idEspecie" => "required|is_natural",
    "nombre" => "required|max_length[30]",
    "tamanio" => "required|in_list[GRANDE,PEQUEÑA]",
  ];
  protected $validationMessages   = [
    "idRaza" => [
      "required" => "El Id de Raza es obligatorio",
      "is_natural" => "El Id de Raza debe ser un número",
    ],
    "idEspecie" => [
      "required" => "El Id de Especie es obligatorio",
      "is_natural" => "El Id de Especie debe ser un número",
    ],
    "nombre" => [
      "required" => "El nombre es obligatorio",
      "max_length" => "Ha excedido la longitud máxima (30)"
    ],
    "tamanio" => [
      "required" => "El tamaño es obligatorio",
      "in_list" => "Debe seleccionar un tamaño"
    ]
  ];
  protected $skipValidation       = false;

  public function nextIdRaza($idEspecie): int {
    $builder = $this->db->table($this->table);
    $row = $builder->select("idRaza")
      ->where("idEspecie", $idEspecie)->orderBy("idRaza", "DESC")
      ->limit(1)->get()->getRowArray();
    return $row ? $row["idRaza"] + 1 : 1;
  }

  protected function objectToRawArray($data, bool $onlyChanged = true, bool $recursive = false): ?array {
    if (method_exists($data, 'toRawArray')) {
      $properties = $data->toRawArray($onlyChanged, $recursive);
    } else {
      $mirror = new ReflectionClass($data);
      $props  = $mirror->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

      $properties = [];

      // Loop over each property,
      // saving the name/value in a new array we can return.
      foreach ($props as $prop) {
        // Must make protected values accessible.
        $prop->setAccessible(true);
        $properties[$prop->getName()] = $prop->getValue($data);
      }
    }

    return $properties;
  }

  /**
   * Método sobrescrito para solventar la clave primaria compuesta
   *
   * @param array $data Data
   * @return bool|Query
   */
  protected function doInsert(array $data) {
    $escape       = $this->escape;
    $this->escape = [];

    if (empty($data[$this->primaryKey[0]]) || empty($data[$this->primaryKey[1]])) {
      throw DataException::forEmptyPrimaryKey('insert');
    }

    $builder = $this->builder();

    // Must use the set() method to ensure to set the correct escape flag
    foreach ($data as $key => $val) {
      $builder->set($key, $val, $escape[$key] ?? null);
    }

    $result = $builder->insert();

    // If insertion succeeded then save the insert ID
    if ($result) {
      $this->insertID = $data[$this->primaryKey[0]] . "-" . $data[$this->primaryKey[1]];
    }
    return $result;
  }

  /**
   * Updates a single record in $this->table.
   * Método sobrescrito para solventar la clave primaria compuesta
   *
   * @param array|int|string|null $primaryKey
   * @param array|null            $data
   */
  protected function doUpdate($primaryKey = null, $data = null): bool {
    $escape       = $this->escape;
    $this->escape = [];

    $idServicio = null;
    $item = null;
    if (is_array($primaryKey)) {
      if (key_exists('idEspecie', $primaryKey) && key_exists('idRaza', $primaryKey)) {
        $idEspecie = intval($primaryKey['idEspecie']);
        $idRaza = intval($primaryKey['idRaza']);
      }
    }

    if (is_string($primaryKey)) {
      $arrayPk = explode("-", $primaryKey);
      $idEspecie = intval($arrayPk[0]);
      $idRaza = intval($arrayPk[1]);
    }

    if ($idEspecie && $idRaza) {
      $builder = $this->builder();
      $builder = $builder->where($this->table . '.idEspecie', $idEspecie)
        ->where($this->table . '.idRaza', $idRaza);

      // Must use the set() method to ensure to set the correct escape flag
      foreach ($data as $key => $val) {
        $builder->set($key, $val, $escape[$key] ?? null);
      }
      return $builder->update();
    }

    return null;
  }

  protected function doDelete($primaryKey = null, bool $purge = false) {
    $builder = $this->builder();

    if (empty($primaryKey)) {
      return false;
    }

    $result = $builder->where("idEspecie", $primaryKey["idEspecie"])
      ->where("idRaza", $primaryKey["idRaza"])->delete();

    return $result;
  }

  public function delete($primaryKey = null, bool $purge = false) {
    if (is_array($primaryKey)) {
      if (!array_key_exists("idEspecie", $primaryKey) || !array_key_exists("idRaza", $primaryKey)) {
        return false;
      }
    }

    $idEspecie = null;
    $idRaza = null;
    if (is_string($primaryKey)) {
      $arrayPk = explode("-", $primaryKey);
      $idEspecie = intval($arrayPk[0]);
      $idRaza = intval($arrayPk[1]);
      $primaryKey = ["idEspecie" => $idEspecie, "idRaza" => $idRaza];
    }

    return $this->doDelete($primaryKey, $purge);
  }

  /**
   * Sobrescribiendo el método find debido a que la clave primaria no es simple sino
   * compuesta
   *
   * @param array|string|null $primaryKey     La clave primaria en array ['idServicio','item'] o Concatenado en string separado por - guión
   * @return DetalleServicioEntity|null       Devuelve un objeto de detalle servicio o null
   */
  public function find($primaryKey = null) {
    $idEspecie = null;
    $idRaza = null;
    if (is_array($primaryKey)) {
      if (key_exists('idEspecie', $primaryKey) && key_exists('idRaza', $primaryKey)) {
        $idEspecie = intval($primaryKey['idEspecie']);
        $idRaza = intval($primaryKey['idRaza']);
      }
    }

    if (is_string($primaryKey)) {
      $arrayPk = explode("-", $primaryKey);
      $idEspecie = intval($arrayPk[0]);
      $idRaza = intval($arrayPk[1]);
    }

    if ($idEspecie && $idRaza) {
      $builder = $this->builder();

      $result = $builder->where($this->table . ".idEspecie", $idEspecie)
        ->where($this->table . ".idRaza", $idRaza)->get()
        ->getFirstRow($this->tempReturnType);
      return $result;
    }
    return null;
  }

  /**
   * Método anulado, se recomienda usar findByIdServicio($idServicio) para recibir una
   * lista de detalle de servicio relacionado con un mismo servicio
   * @ignore
   */
  public function findAll(int $limit = 0, int $offset = 0) {
    return [];
  }

  /**
   * Buscar detalles de servicios mediante el id de Servicios
   * @param int $idServicio
   * @return array|null
   */
  public function findByIdEspecie(int $idEspecie) {
    $builder = $this->db->table($this->table);
    $result = $builder->where("idEspecie", $idEspecie)
      ->get()->getResult($this->tempReturnType);
    return $result;
  }
}
