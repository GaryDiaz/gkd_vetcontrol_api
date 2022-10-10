<?php

namespace App\Controllers;

use App\Beans\PropietarioBean;
use CodeIgniter\RESTful\ResourceController;


class PropietarioController extends ResourceController {
  protected $modelName = "App\Models\PropietarioModel";
  protected $format = "json";

  public function index() {
    if ($propietarios = $this->model->findAll()) {
      return $this->respond([
        "data" => PropietarioBean::arrayEntitiesToBeans($propietarios)
      ]);
    }
    return $this->failNotFound("No se encontraron propietarios");
  }

  public function show($id = null) {
    if ($propietario = $this->model->find($id)) {
      return $this->respond([
        "data" => new PropietarioBean($propietario)
      ]);
    }
    return $this->failNotFound("No se encontró ningún propietario con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $vb = PropietarioBean::getInstanceCreateForm($form);
    $propietario = $vb->getPropietarioEntity();

    if (!$id = $this->model->insert($propietario)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $pro = $this->model->find($id);
    return $this->respond([
      "message" => "El propietario ha sido registrado satisfactoriamente",
      "data" => new PropietarioBean($pro)
    ]);
  }

  public function update($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("El propietario que intenta editar no existe");
    }

    $data = PropietarioBean::extraerDatosActualizables($form);
    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos del propietario actualizados con éxito",
      "data" => new PropietarioBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    if (!$this->model->find($id)) {
      return $this->failNotFound("El propietario que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "El Propietario con id $id has sido eliminado satisfactoriamente"
    ]);
  }
}
