<?php
namespace core;

class Locale {

  protected $locale;
  protected $options;

  public function __construct($locale = null) {
    if (is_null($locale)) {
      $locale = locale_get_default();
    }
    $this->locale = $locale;
    $this->options = localeconv();
  }

  public function get_primary_language() {
    return locale_get_primary_language($this->locale);
  }

  public function __set($name, $value) {
    $this->options[$name] = $value;
  }

  public function __get($name) {
    return $this->options[$name];
  }

  public function __isset($name) {
    return isset($this->options[$name]);
  }

  public function __unset($name) {
    unset($this->options[$name]);
  }

  static private $instance = null;

  static public function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new Locale();
    }
    return self::$instance;
  }
}
