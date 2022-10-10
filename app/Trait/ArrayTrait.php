<?php

namespace App\Traits;

trait ArrayTrait {
  /**
   * Si un campo existe en un array devuelve el valor o devuelve null
   * @param string $nombreCampo
   * @param array $data
   */
  public static function tomarSiExiste(string $nombreCampo, array $data) {
    return array_key_exists($nombreCampo, $data) ? $data[$nombreCampo] : null;
  }

  public static function filtrarCampos(array $keys, array $fullData): array {
    $data = [];
    foreach ($keys as $key) {
      if (array_key_exists($key, $fullData)) {
        $data[$key] = $fullData[$key];
      }
    }
    return $data;
  }
}
