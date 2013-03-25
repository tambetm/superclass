<?php
namespace helpers\base;

class Session {

  static public function get($name) {
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
  }

  static public function set($name, $value) {
    $_SESSION[$name] = $value;
  }

  static public function remove($name) {
    unset($_SESSION[$name]);
  }
}
