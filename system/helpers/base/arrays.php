<?php
namespace helpers\base;

class Arrays {

  static public function get($array, $name, $default = null) {
    return isset($array[$name]) ? $array[$name] : $default;
  }
}
