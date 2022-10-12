<?php

namespace App\Beans;

use App\Entities\CitaEntity;
use App\Traits\ArrayTrait;

class CitaBean {
  /**
   * @var int
   */
  public $id;
  /**
   * @var int
   */
  public $idPaciente;
  /**
   * @var int
   */
  public $idVeterinario;
  /**
   * @var string
   */
  public $fecha;
  /**
   * @var string
   */
  public $motivo;
  /**
   * @var string
   */
  public $estatus;

  public function __construct(CitaEntity $cita = null) {
    if ($cita) {
      $this->id = $cita->id;
      $this->idPaciente = $cita->idPaciente;
      $this->idVeterinario = $cita->idVeterinario;
      $this->fecha = $cita->fecha;
      $this->motivo = $cita->motivo;
      $this->estatus = $cita->estatus;
    }
  }

  public function getCitaEntity(): CitaEntity {
    return new CitaEntity(array(
      "idCita" => $this->id,
      "idPaciente" => $this->idPaciente,
      "idVeterinario" => $this->idVeterinario,
      "fecha" => $this->fecha,
      "motivo" => $this->motivo,
      "estatus" => $this->estatus,
    ));
  }

  public function setCitaEntity(CitaEntity $cita) {
    $this->id = $cita->id;
    $this->idPaciente = $cita->idPaciente;
    $this->idVeterinario = $cita->idVeterinario;
    $this->fecha = $cita->fecha;
    $this->motivo = $cita->motivo;
    $this->estatus = $cita->estatus;
  }

  public static function arrayEntitiesToBeans(array $citas): array {
    $beans = [];
    for ($i = 0; $i < count($citas); $i++) {
      $cita = $citas[$i];
      $cb = new PacienteBean($cita);
      $beans[$i] = $cb;
    }
    return $beans;
  }

  public static function getInstanceCreateForm(array $form): CitaBean {
    $cb = new CitaBean();
    $cb->id = 0;
    $cb->idPaciente = $form["idPaciente"];
    $cb->idVeterinario = $form["idVeterinario"];
    $cb->fecha = $form["fecha"];
    $cb->motivo = $form["motivo"];
    $cb->estatus = $form["estatus"];
    return $cb;
  }

  public static function extraerDatosActualizables(array $form): array {
    $keys = [
      "idCita", "idPaciente", "idVeterinario", "fecha", "motivo", "estatus"
    ];
    $data = ArrayTrait::filtrarCampos($keys, $form);
    return $data;
  }
}
