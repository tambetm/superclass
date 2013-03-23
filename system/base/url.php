<?php
namespace base;

class URL {

  public static function current_url() {    
    if (isset($_SERVER['PATH_INFO'])) {
      return ltrim($_SERVER['PATH_INFO'], '/');
    } else {
      return strtolower(DEFAULT_CONTROLLER).'/'.DEFAULT_METHOD;
    }
  }

  public static function base_path() {
    return str_replace(self::current_url(), '', $_SERVER['REQUEST_URI']);
  }

  public static function base_url() {
    return self::host_url().self::base_path();
  }

  public static function relative_url($path) {
    return dirname(self::current_url()).'/'.$path;
  }

  public static function protocol() {
    return isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
  }

  public static function host_name() {
    return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
  }

  public static function host_url() {
    return self::protocol().self::host_name();
  }

  public static function redirect($url) {
    if (strpos($url, 'http:') !== 0) {
      $url = self::base_url().$url;
    }
    header('Location: '.$url);
    exit;
  }
}
