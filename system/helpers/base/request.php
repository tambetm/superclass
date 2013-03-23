<?php
namespace helpers\base;

class Request {

  static public function method() {
    return $_SERVER['REQUEST_METHOD'];
  }

  static public function get($name) {
    return isset($_GET[$name]) ? $_GET[$name] : null;
  }

  static public function post($name) {
    return isset($_POST[$name]) ? $_POST[$name] : null;
  }

  static public function request($name) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
  }

  static public function cookie($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
  }
}
