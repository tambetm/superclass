<?php
namespace helpers\base;

class URL {

  public static function current_path() {
    return $_SERVER['REQUEST_URI'];
  }

  // relative path starting from base_path()
  public static function relative_path() {
    if (isset($_SERVER['PATH_INFO'])) {
      return ltrim($_SERVER['PATH_INFO'], '/');
    } else {
      return strtolower(DEFAULT_RESOURCE).'/'.DEFAULT_ACTION;
    }
  }

  public static function base_path() {
    return str_replace(self::relative_path(), '', self::current_path());
  }

  public static function current_url() {
    return self::host_url().self::current_path();
  }

  public static function base_url() {
    return self::host_url().self::base_path();
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
}
