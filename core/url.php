<?php

class core_URL {

  public static function base_url() {
    $current_url = trim(self::current_url(), '/');
    $base_url = str_replace($current_url, '', $_SERVER['REQUEST_URI']);
    return $base_url;
  }

  public static function current_url() {
    
    if (isset($_SERVER['PATH_INFO'])) {
      return $_SERVER['PATH_INFO'];
    } else {
      return '/'.strtolower(DEFAULT_CONTROLLER).'/'.DEFAULT_METHOD;
    }
  }
}
