<?php
namespace helpers\base;

use helpers\Arrays;

class URL {

  public static function self() {
    $segments = self::segments();
    $index = 0;
    if (USE_COLLECTION) {
      $segments[$index] = Arrays::get($segments, $index, DEFAULT_COLLECTION);
      $index++;
    }
    $segments[$index] = Arrays::get($segments, $index, DEFAULT_RESOURCE);
    $index++;
    $segments[$index] = Arrays::get($segments, $index, DEFAULT_ACTION);

    $args = func_get_args();
    // non-array argument replaces last part of the url
    if (isset($args[0]) && !is_array($args[0])) {
      $path = array_shift($args);
      $segments[count($segments) - 1] = $path;
    }
    $url = self::base_path().implode('/', $segments);
    if (isset($args[0]) && is_array($args[0])) {
      // array argument is added as URL parameters
      // allow reset url parameters with array()
      if (!empty($args[0])) {
        $url .= '?'.http_build_query($args[0]);
      }
    } elseif ($_SERVER['QUERY_STRING']) {
      // otherwise current request parameters are added
      $url .= '?'.$_SERVER['QUERY_STRING'];
    }
    return $url;
  }

  public static function segments() {
    // use static variable to cache segments calls
    static $segments = null;

    if (is_null($segments)) {
      if (isset($_SERVER['PATH_INFO'])) {
        $segments = explode('/', trim($_SERVER['PATH_INFO'], '/'));
      } else {
        $segments = array();
      }
    }

    return $segments;
  }

  public static function get_resource() {
    $segments = self::segments();
    if (USE_COLLECTION) {
      Arrays::get($segments, 0, DEFAULT_COLLECTION).'/'.Arrays::get($segments, 1, DEFAULT_RESOURCE);
    } else {
      return Arrays::get($segments, 0, DEFAULT_RESOURCE);
    }
  }

  public static function get_action() {
    $segments = self::segments();
    return Arrays::get($segments, USE_COLLECTION ? 2 : 1, DEFAULT_ACTION);
  }

  public static function get_subaction() {
    $segments = self::segments();
    return Arrays::get($segments, USE_COLLECTION ? 3 : 2, DEFAULT_SUBACTION);
  }

  public static function current_path() {
    return $_SERVER['REQUEST_URI'];
  }

  // relative path starting from base_path()
  public static function site_path() {
    if (isset($_SERVER['PATH_INFO'])) {
      return ltrim($_SERVER['PATH_INFO'], '/');
    } else {
      return (USE_COLLECTION ? DEFAULT_COLLECTION.'/' : '').DEFAULT_RESOURCE.'/'.DEFAULT_ACTION;
    }
  }

  public static function base_path() {
    return dirname($_SERVER['SCRIPT_NAME']).'/';
  }

  public static function self_url($path = null, $params = null) {
    return self::host_url().self::self($path, $params);
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
