<?php
namespace base;

class Session {
  static private $instance = null;

  static public function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new Session();
    }
    return self::$instance;
  }

  private function __construct() {
    session_start();
  }

  public function __get($name) {
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
  }

  public function __set($name, $value) {
    $_SESSION[$name] = $value;
  }

  public function __isset($name) {
    return isset($_SESSION[$name]);
  }

  public function __unset($name) {
    unset($_SESSION[$name]);
  }
}