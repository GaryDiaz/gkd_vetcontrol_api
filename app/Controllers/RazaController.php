<?php

namespace App\Controllers;

use App\Beans\RazaBean;
use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;

class RazaController extends ResourceController {
  protected $modelName = "App\Models\RazaModel";
  protected $format = "json";

  /**
   * Lista las Razas a partir del Id de Especie
   * @param int $id Id de Especie
   */
  public function list($id = null) {
    if ($razas = $this->model->findByIdEspecie($id)) {
      return $this->respond([
        "data" => RazaBean::arrayEntitiesToBeans($razas)
      ]);
    }
    return $this->failNotFound("No se encontraron razas");
  }

  /**
   * 
   */
  public function show($id = null) {
    $validacionPk = $this->validarClavePrimaria($id);
    if (!$validacionPk["ok"]) {
      return $this->fail($validacionPk["message"]);
    }
    $pk = $validacionPk["arrayPk"];
    if ($raza = $this->model->find($pk)) {
      return $this->respond([
        "data" => new RazaBean($raza)
      ]);
    }
    return $this->failNotFound("No se encontró ninguna raza con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $rb = RazaBean::getInstanceCreateForm($form);
    $raza = $rb->getRazaEntity();
    $raza->idRaza = $this->model->nextIdRaza($rb->idEspecie);

    if (!$id = $this->model->insert($raza)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $raza = $this->model->find($id);
    return $this->respond([
      "message" => "La Raza ha sido registrada satisfactoriamente",
      "data" => new RazaBean($raza)
    ]);
  }

  public function update($id = null) {
    $validacionPk = $this->validarClavePrimaria($id);
    if (!$validacionPk["ok"]) {
      return $this->fail($validacionPk["message"]);
    }

    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que actualizar");
    }

    $pk = $validacionPk["arrayPk"];
    if (!$this->model->find($pk)) {
      return $this->failNotFound("La raza que intenta editar no existe");
    }

    $data = RazaBean::extraerDatosActualizables($form);
    if (!$this->model->update($pk, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $raza = $this->model->find($pk);
    return $this->respondUpdated([
      "message" => "La raza ha sido actualizada con éxito",
      "data" => new RazaBean($raza)
    ]);
  }

  public function delete($id = null) {
    $validacionPk = $this->validarClavePrimaria($id);
    if (!$validacionPk["ok"]) {
      return $this->fail($validacionPk["message"]);
    }

    $pk = $validacionPk["arrayPk"];
    if (!$this->model->find($pk)) {
      return $this->failNotFound("La raza que intenta eliminar no existe");
    }

    if (!$this->model->delete($pk)) {
      return $this->fail("No se pudo eliminar la raza con $id");
    }

    return $this->respondDeleted([
      "message" => "La raza con id $id has sido eliminada satisfactoriamente",
    ]);
  }

  /**
   * Válida que esté bien conformada la clave primaria compuesta y devuelve un array
   * con un valor boolean llamado ok, si "ok"=false, devuelve un "message" con la descripcion
   * del error, si "ok"=true, devuelve un "arrayPk" con los valores de la clave primaria
   * compuesta con sus respectivos campos
   * @param string $pk clave primaria concatenada
   * @return array con un valor boolean de nombre ok
   */
  private function validarClavePrimaria(string $primaryKey) {
    $arrayPk = explode("-", $primaryKey);
    if (count($arrayPk) !== 2) {
      return [
        "ok" => false,
        "message" => "El id de raza de servicio debe estar combinado idEspecie-idRaza (separado por guión)"
      ];
    }
    [$idEspecie, $idRaza] = $arrayPk;
    if (!is_numeric($idEspecie) || !is_numeric($idRaza)) {
      return [
        "ok" => false,
        "message" => "El id de raza y id de especie son valores numéricos separados por guión"
      ];
    }

    return [
      "ok" => true,
      "arrayPk" => [
        "idEspecie" => intval($idEspecie),
        "idRaza" => intval($idRaza)
      ]
    ];
  }
}
