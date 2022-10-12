<?php

namespace App\Controllers;

use App\Beans\CitaBean;
use CodeIgniter\RESTful\ResourceController;

class CitaController extends ResourceController {
  protected $modelName = "App\Models\CitaModel";
  protected $format = "json";

  public function index() {
    if ($citas = $this->model->findAll()) {
      return $this->respond([
        "data" => CitaBean::arrayEntitiesToBeans($citas)
      ]);
    }
    return $this->failNotFound("No se encontraron citas");
  }

  public function show($id = null) {
    if ($cita = $this->model->find($id)) {
      return $this->respond([
        "data" => new CitaBean($cita)
      ]);
    }
    return $this->failNotFound("No se encontró ninguna cita con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $cb = CitaBean::getInstanceCreateForm($form);
    $cita = $cb->getCitaEntity();

    if (!$id = $this->model->insert($cita)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $pro = $this->model->find($id);
    return $this->respond([
      "message" => "La cita ha sido registrada satisfactoriamente",
      "data" => new CitaBean($pro)
    ]);
  }

  public function update($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("La cita que intenta editar no existe");
    }

    $data = CitaBean::extraerDatosActualizables($form);
    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos de la cita actualizados con éxito",
      "data" => new CitaBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    if (!$this->model->find($id)) {
      return $this->failNotFound("La cita que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "La cita con id $id has sido eliminado satisfactoriamente"
    ]);
  }
}
