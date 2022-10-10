<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class UsuarioModel extends Model {
  protected $DBGroup          = 'default';
  protected $table            = 'usuario';
  protected $primaryKey       = 'idUsuario';
  protected $useAutoIncrement = false;
  protected $insertID         = 0;
  protected $returnType       = UsuarioEntity::class;
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'idUsuario',
    'nick',
    'apiKey',
    'clave',
    'rol',
    'nombre',
    'apellido',
    'estatus',
    'ultimoAcceso'
  ];

  // Validation
  protected $validationRules    = [
    'idUsuario' => 'required|is_unique[usuario.idUsuario]',
    'nick' => 'required|max_length[20]|is_unique[usuario.nick]',
    'clave' => 'required',
    'rol' => 'required|max_length[20]',
    'nombre' => 'required|max_length[20]',
    'apellido' => 'required|max_length[20]',
    'estatus' => 'required|in_list[ACTIVO,INACTIVO]',
  ];
  protected $validationMessages = [
    'idUsuario' => [
      'required' => 'El id de usuario es obligatorio',
      'is_unique' => 'El id de usuario ingresado ya existe',
    ],
    'nick' => [
      'required' => 'El nick es obligatorio',
      'is_unique' => 'Ese nick ya existe',
    ],
    'clave' => [
      'required' => 'Debe proporcionar una clave'
    ],
    'rol' => [
      'required' => 'Debe seleccionar un rol',
      'max_length' => 'Se ha excedido del máximo de caracteres (20)'
    ],
    'nombre' => [
      'required' => 'El nombre es obligatorio',
      'max_length' => 'Se ha excedido del máximo de caracteres (20)'
    ],
    'apellido' => [
      'required' => 'El nombre es obligatorio',
      'max_length' => 'Se ha excedido del máximo de caracteres (20)'
    ],
    'estatus' => [
      'required' => 'Debe seleccionar un estatus',
      "in_list" => "Debe seleccionar un estatus"
    ],
  ];
  protected $skipValidation       = false;

  public function autorizar(string $nick) {
    $usuario = $this->findByNick($nick);
    if (!$usuario) {
      return null;
    }
    $usuario["ultimoAcceso"] = date("Y-m-d H:i:s");
    $usuario["apiKey"] = $this->crearApiKey($usuario);
    if (!$this->update($usuario["idUsuario"], [
      "apiKey" => $usuario["apiKey"],
      "ultimoAcceso" => $usuario["ultimoAcceso"]
    ])) {
      return null;
    }

    return $usuario;
  }

  private function crearApiKey(array $usuario): string {
    $dt = new DateTime($usuario["ultimoAcceso"]);
    $n = $dt->getTimestamp() % 99;
    $salt = ($n > 9) ? "" . $n : "0" . $n;
    $hash = crypt($usuario["nick"], $salt);
    return $hash;
  }

  public function clavePredeterminada(): string {
    return $this->encriptarClave("12345");
  }

  public function encriptarClave($clave): string {
    $salt = getenv('GKD_SC_SALT');
    return crypt($clave, $salt);
  }

  public function findByNick(string $nick) {
    $builder = $this->db->table($this->table);
    $builder->where("nick", $nick);
    $row = $builder->get()->getRowArray();
    return $row;
  }

  public function nextId(): int {
    $builder = $this->db->table($this->table);
    $row = $builder->select("idUsuario")->orderBy("idUsuario", "DESC")
      ->limit(1)->get()->getRowArray();
    return $row ? $row["idUsuario"] + 1 : 1;
  }
}
