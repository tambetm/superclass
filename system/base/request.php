<?php
namespace base;

class Request {

  static private $instance = null;

  static public function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new Request();
    }
    return self::$instance;
  }

  public function method() {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function __get($name) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
  }

  public function __set($name, $value) {
    $_REQUEST[$name] = $value;
  }

  public function __isset($name) {
    return isset($_REQUEST[$name]);
  }

  public function __unset($name) {
    unset($_REQUEST[$name]);
  }

  public function post($name) {
    return isset($_POST[$name]) ? $_POST[$name] : null;
  }

  public function get($name) {
    return isset($_GET[$name]) ? $_GET[$name] : null;
  }

  public function cookie($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
  }
}
