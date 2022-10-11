<?php

namespace App\Beans;

use App\Entities\PacienteEntity;
use App\Traits\ArrayTrait;

class PacienteBean {
  /**
   * @var int
   */
  public $id;
  /**
   * @var int
   */
  public $idHistoriaClinica;
  /**
   * @var int
   */
  public $idPropietario;
  /**
   * @var string
   */
  public $nombre;
  /**
   * @var int
   */
  public $idEspecie;
  /**
   * @var int
   */
  public $idRaza;
  /**
   * @var string
   */
  public $color;
  /**
   * @var string
   */
  public $sexo;
  /**
   * @var string
   */
  public $fechaNacimiento;
  public $seniasParticulares;
  /**
   * @var string
   */
  public $procedencia;
  /**
   * @var string
   */
  public $estatus;

  public function __construct(PacienteEntity $paciente = null) {
    if ($paciente) {
      $this->id = $paciente->id;
      $this->idHistoriaClinica = $paciente->idHistoriaClinica;
      $this->idPropietario = $paciente->idPropietario;
      $this->nombre = $paciente->nombre;
      $this->idEspecie = $paciente->idEspecie;
      $this->idRaza = $paciente->idRaza;
      $this->color = $paciente->color;
      $this->sexo = $paciente->sexo;
      $this->fechaNacimiento = $paciente->fechaNacimiento;
      $this->seniasParticulares = $paciente->seniasParticulares;
      $this->procedencia = $paciente->procedencia;
      $this->estatus = $paciente->estatus;
    }
  }

  public function getPacienteEntity(): PacienteEntity {
    return new PacienteEntity(array(
      "idPaciente" => $this->id,
      "idHistoriaClinica" => $this->idHistoriaClinica,
      "idPropietario" => $this->idPropietario,
      "nombre" => $this->nombre,
      "idEspecie" => $this->idEspecie,
      "idRaza" => $this->idRaza,
      "color" => $this->color,
      "sexo" => $this->sexo,
      "fechaNacimiento" => $this->fechaNacimiento,
      "seniasParticulares" => $this->seniasParticulares,
      "procedencia" => $this->procedencia,
      "estatus" => $this->estatus,
    ));
  }

  public function setPacienteEntity(PacienteEntity $paciente) {
    $this->id = $paciente->id;
    $this->idHistoriaClinica = $paciente->idHistoriaClinica;
    $this->idPropietario = $paciente->idPropietario;
    $this->nombre = $paciente->nombre;
    $this->idEspecie = $paciente->idEspecie;
    $this->idRaza = $paciente->idRaza;
    $this->color = $paciente->color;
    $this->sexo = $paciente->sexo;
    $this->fechaNacimiento = $paciente->fechaNacimiento;
    $this->seniasParticulares = $paciente->seniasParticulares;
    $this->procedencia = $paciente->procedencia;
    $this->estatus = $paciente->estatus;
  }

  public static function arrayEntitiesToBeans(array $pacientes): array {
    $beans = [];
    for ($i = 0; $i < count($pacientes); $i++) {
      $paciente = $pacientes[$i];
      $pb = new PacienteBean($paciente);
      $beans[$i] = $pb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): PacienteBean {
    $pb = new PacienteBean();
    $pb->id = 0;
    $pb->idHistoriaClinica = $form["idHistoriaClinica"];
    $pb->idPropietario = $form["idPropietario"];
    $pb->nombre = $form["nombre"];
    $pb->idEspecie = $form["idEspecie"];
    $pb->idRaza = $form["idRaza"];
    $pb->color = $form["color"];
    $pb->sexo = $form["sexo"];
    $pb->fechaNacimiento = $form["fechaNacimiento"];
    $pb->seniasParticulares = $form["seniasParticulares"];
    $pb->procedencia = $form["procedencia"];
    $pb->estatus = $form["estatus"];
    return $pb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = [
      "idPropietario", "nombre", "idEspecie", "idRaza",
      "color", "sexo", "fechaNacimiento", "seniasParticulares", "procedencia"
    ];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
