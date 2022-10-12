<?php

namespace App\Controllers;

use App\Beans\PacienteBean;
use CodeIgniter\RESTful\ResourceController;

class PacienteController extends ResourceController {
  protected $modelName = "App\Models\PacienteModel";
  protected $format = "json";

  public function index() {
    if ($pacientes = $this->model->findAll()) {
      return $this->respond([
        "data" => PacienteBean::arrayEntitiesToBeans($pacientes)
      ]);
    }
    return $this->failNotFound("No se encontraron pacientes");
  }

  public function show($id = null) {
    if ($paciente = $this->model->find($id)) {
      return $this->respond([
        "data" => new PacienteBean($paciente)
      ]);
    }
    return $this->failNotFound("No se encontró ningún paciente con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $pb = PacienteBean::getInstanceCreateForm($form);
    $paciente = $pb->getPacienteEntity();

    if (!$id = $this->model->insert($paciente)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $pro = $this->model->find($id);
    return $this->respond([
      "message" => "El paciente ha sido registrado satisfactoriamente",
      "data" => new PacienteBean($pro)
    ]);
  }

  public function update($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("El paciente que intenta editar no existe");
    }

    $data = PacienteBean::extraerDatosActualizables($form);
    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos del paciente actualizados con éxito",
      "data" => new PacienteBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    if (!$this->model->find($id)) {
      return $this->failNotFound("El paciente que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "El paciente con id $id has sido eliminado satisfactoriamente"
    ]);
  }
}
