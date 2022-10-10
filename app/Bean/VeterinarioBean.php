<?php

namespace App\Beans;

use App\Entities\VeterinarioEntity;
use App\Traits\ArrayTrait;

class VeterinarioBean {
  public $id;
  public $idUsuario;
  public $nombre;
  public $apellido;
  public $telefono;
  public $email;
  public $estatus;
  public $usuario;

  public function __construct(VeterinarioEntity $veterinario = null) {
    if ($veterinario) {
      $this->id = $veterinario->id;
      $this->idUsuario = $veterinario->idUsuario;
      $this->nombre = $veterinario->nombre;
      $this->apellido = $veterinario->apellido;
      $this->telefono = $veterinario->telefono;
      $this->email = $veterinario->email;
      $this->estatus = $veterinario->estatus;
    }
  }

  public function getNombreCompleto(): string {
    return $this->nombre . " " . $this->apellido;
  }

  public function getVeterinarioEntity(): VeterinarioEntity {
    return new VeterinarioEntity(array(
      "idVeterinario" => $this->id,
      "idUsuario" => $this->idUsuario,
      "nombre" => $this->nombre,
      "apellido" => $this->apellido,
      "telefono" => $this->telefono,
      "email" => $this->email,
      "estatus" => strtoupper($this->estatus),
    ));
  }

  public function setVeterinarioEntity(VeterinarioEntity $veterinario) {
    $this->id = $veterinario->id;
    $this->idUsuario = $veterinario->idUsuario;
    $this->nombre = $veterinario->nombre;
    $this->apellido = $veterinario->apellido;
    $this->telefono = $veterinario->telefono;
    $this->email = $veterinario->email;
    $this->estatus = $veterinario->estatus;
  }

  public static function arrayEntitiesToBeans(array $veterinarios): array {
    $beans = [];
    for ($i = 0; $i < count($veterinarios); $i++) {
      $veterinario = $veterinarios[$i];
      $vb = new UsuarioBean($veterinario);
      $beans[$i] = $vb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): VeterinarioBean {
    $vb = new VeterinarioBean();
    $vb->id = 0;
    $vb->idUsuario = $form["idUsuario"];
    $vb->nombre = $form["nombre"];
    $vb->apellido = $form["apellido"];
    $vb->telefono = $form["telefono"];
    $vb->email = $form["email"];
    $vb->estatus = $form["estatus"];
    return $vb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = ["nombre", "apellido", "telefono", "email"];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
