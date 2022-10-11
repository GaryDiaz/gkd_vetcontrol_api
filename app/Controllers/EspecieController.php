<?php

namespace App\Controllers;

use App\Beans\EspecieBean;
use CodeIgniter\RESTful\ResourceController;

class EspecieController extends ResourceController {
  protected $modelName = "App\Models\EspecieModel";
  protected $format = "json";

  public function index() {
    if ($especies = $this->model->findAll()) {
      return $this->respond([
        "data" => EspecieBean::arrayEntitiesToBeans($especies)
      ]);
    }
    return $this->failNotFound("No se encontraron especies");
  }

  public function show($id = null) {
    if ($especie = $this->model->find($id)) {
      return $this->respond([
        "data" => new EspecieBean($especie)
      ]);
    }
    return $this->failNotFound("No se encontró ningún especie con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $eb = EspecieBean::getInstanceCreateForm($form);
    $especie = $eb->getEspecieEntity();

    if (!$id = $this->model->insert($especie)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $esp = $this->model->find($id);
    return $this->respond([
      "message" => "La Especie ha sido registrada satisfactoriamente",
      "data" => new EspecieBean($esp)
    ]);
  }

  public function update($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("La especie que intenta editar no existe");
    }

    $data = EspecieBean::extraerDatosActualizables($form);
    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos de la especie actualizados con éxito",
      "data" => new EspecieBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    if (!$this->model->find($id)) {
      return $this->failNotFound("La especie que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "La especie con id $id has sido eliminada satisfactoriamente"
    ]);
  }
}
