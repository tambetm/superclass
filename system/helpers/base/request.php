<?php
namespace helpers\base;

class Request {

  static public function method() {
    return $_SERVER['REQUEST_METHOD'];
  }

  static public function get($name = null, $default = null) {
    return isset($_GET[$name]) ? $_GET[$name] : $default;
  }

  static public function post($name = null, $default = null) {
    return isset($_POST[$name]) ? $_POST[$name] : $default;
  }

  static public function request($name = null, $default = null) {
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
  }

  // Do we need this alias? I like it for readability - Request::param('name').
  // Or maybe we should drop Request::request() instead? That name is confusing.
  static public function param($name = null, $default = null) {
    self::request($name, $default);
  }

  static public function cookie($name = null, $default = null) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
  }
}
