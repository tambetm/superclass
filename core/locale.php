<?php

class core_Locale {

  public function __construct($locale = null) {
    if (is_null($locale)) {
      $locale = locale_get_default();
    }
    $this->locale = $locale;
  }

  public function get_primary_language() {
    return locale_get_primary_language($this->locale);
  }

  static private $instance = null;

  static public function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new core_Locale();
    }
    return self::$instance;
  }
}
