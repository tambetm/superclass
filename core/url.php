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

  public static function relative_url($path) {
    $current_url = ltrim(self::current_url(), '/');
    return dirname($current_url).'/'.$path;
  }

  public static function protocol() {
    return $_SERVER['HTTPS'] ? 'https://' : 'http://';
  }

  public static function host_name() {
    return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
  }

  public static function host_url() {
    return self::protocol().self::host_name();
  }

  public static function redirect($url) {
    if (strpos($url, 'http:') !== 0) {
      $url = self::host_url().self::base_url().self::relative_url($url);
    }
    header('Location: '.$url);
  }
}
