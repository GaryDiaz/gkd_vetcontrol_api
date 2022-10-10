<?php

namespace App\Controllers;

use App\Beans\VeterinarioBean;
use CodeIgniter\RESTful\ResourceController;

class VeterinarioController extends ResourceController {
  protected $modelName = "App\Models\VeterinarioModel";
  protected $format = "json";

  public function index() {
    if ($veterinarios = $this->model->findAll()) {
      return $this->respond([
        "data" => VeterinarioBean::arrayEntitiesToBeans($veterinarios)
      ]);
    }
    return $this->failNotFound("No se encontraron veterinarios");
  }

  public function show($id = null) {
    if ($veterinario = $this->model->find($id)) {
      return $this->respond([
        "data" => new VeterinarioBean($veterinario)
      ]);
    }
    return $this->failNotFound("No se encontró ningún veterinario con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $vb = VeterinarioBean::getInstanceCreateForm($form);
    $veterinario = $vb->getVeterinarioEntity();

    if (!$id = $this->model->insert($veterinario)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $vet = $this->model->find($id);
    return $this->respond([
      "message" => "El veterinario ha sido registrado satisfactoriamente",
      "data" => new VeterinarioBean($vet)
    ]);
  }

  public function update($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("El usuario que intenta editar no existe");
    }

    $data = VeterinarioBean::extraerDatosActualizables($form);
    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos del veterinario actualizados con éxito",
      "data" => new VeterinarioBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    if (!$this->model->find($id)) {
      return $this->failNotFound("El usuario que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "El Usuario con id $id has sido eliminado satisfactoriamente"
    ]);
  }
}
