<?php

namespace App\Controllers;

use App\Beans\AccesoBean;
use App\Beans\UsuarioBean;
use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class UsuarioController extends ResourceController {
  protected $modelName = "App\Models\UsuarioModel";
  protected $format = "json";

  public function index() {
    if ($usuarios = $this->model->findAll()) {
      return $this->respond([
        "data" => UsuarioBean::arrayEntitiesToBeans($usuarios)
      ]);
    }
    return $this->failNotFound("No se encontraron usuarios");
  }

  public function show($id = null) {
    if ($usuario = $this->model->find($id)) {
      return $this->respond([
        "data" => new UsuarioBean($usuario)
      ]);
    }
    return $this->failNotFound("No se encontró ningún usuario con id $id");
  }

  public function create() {
    $form = $this->request->getJSON(true);
    $ub = UsuarioBean::getInstanceCreateForm($form);
    $usuario = $ub->getUsuarioEntity();
    $usuario->clave = $this->model->clavePredeterminada();
    $usuario->idUsuario = $this->model->nextId();

    if (!$id = $this->model->insert($usuario)) {
      return $this->failValidationErrors($this->model->errors());
    }

    $usr = $this->model->find($id);
    return $this->respond([
      "message" => "El usuario ha sido registrado satisfactoriamente",
      "data" => new UsuarioBean($usr)
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

    $nombre = $form["nombre"];
    $apellido = $form["apellido"];
    $data = ["nombre" => $nombre, "apellido" => $apellido];

    if (!$this->model->update($id, $data)) {
      return $this->failValidationErrors($this->model->errors());
    }

    return $this->respondUpdated([
      "message" => "Datos de usuario actualizado con éxito",
      "data" => new UsuarioBean($this->model->find($id))
    ]);
  }

  public function delete($id = null) {
    $form = $this->request->getJSON(true);
    if (empty($form)) {
      return $this->failValidationErrors("Nada que hacer");
    }

    if (!$this->model->find($id)) {
      return $this->failNotFound("El usuario que intenta eliminar no existe");
    }

    $this->model->delete($id);

    return $this->respondDeleted([
      "message" => "El Usuario con id $id has sido eliminado satisfactoriamente"
    ]);
  }

  public function login() {
    $loginForm = $this->request->getJSON(true);
    $nick = $loginForm["nick"];
    $clave = $loginForm["clave"];

    $usuario = $this->model->findByNick($nick);
    if (!$usuario) {
      return $this->failNotFound("No se encontró ningún usuario con el nick $nick");
    }
    $claveEncriptada = $this->model->encriptarClave($clave);
    if ($usuario["clave"] !== $claveEncriptada) {
      return $this->failUnauthorized("Su clave y nick no coinciden");
    }
    if (!$usuario = $this->model->autorizar($usuario["nick"])) {
      return $this->failUnauthorized(
        "Fallo el proceso de autorización, intente más tarde"
      );
    }
    $token = AccesoBean::getInstanceDataArray($usuario)->generarToken();
    return $this->respond([
      "message" => "Validación exitosa, es un placer recibirle {$usuario["nombre"]} {$usuario["apellido"]}",
      "token" => $token
    ]);
  }

  public function cambiarClave() {
    $form = $this->request->getJSON(true);
    $claveActual = $form["claveActual"];
    $claveNueva = $form["claveNueva"];
    $claveConfirmacion = $form["claveConfirmacion"];
    if ($claveNueva !== $claveConfirmacion) {
      return $this->fail("Clave no confirmada");
    }

    try {
      $usuario = $_REQUEST["usuario"];
      $claveActualCript = $this->model->encriptarClave($claveActual);
      if ($usuario["clave"] !== $claveActualCript) {
        return $this->failUnauthorized("Clave actual no coincide");
      }
      $claveNuevaCript = $this->model->encriptarClave($claveNueva);
      if (!$this->model->update($usuario["idUsuario"], ["clave" => $claveNuevaCript])) {
        return $this->fail("No se pudo actualizar la clave");
      }
      return $this->respond([
        "message" => "Clave actualizada satisfactoriamente",
      ]);
    } catch (Exception $th) {
      return $this->fail($th->getMessage());
    }
  }
}
