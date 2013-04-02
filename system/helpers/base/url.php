<?php
namespace helpers\base;

use helpers\Arrays;

class URL {

  public static function self() {
    $segments = self::segments();
    $args = func_get_args();
    // non-array argument replaces last part of the url
    if (isset($args[0]) && !is_array($args[0])) {
      $path = array_shift($args);
      if (!is_null($path)) {
        $segments[count($segments) - 1] = $path;
      }
    }
    $url = self::base_path().implode('/', $segments);
    if (isset($args[0]) && is_array($args[0])) {
      // allow reset url parameters with array()
      if (!empty($args[0])) {
        // array argument is added as URL parameters
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

      // assign default values to segments
      $index = 0;
      if (USE_NAMESPACE) {
        $segments[$index] = Arrays::get($segments, $index, DEFAULT_NAMESPACE);
        $index++;
      }
      $segments[$index] = Arrays::get($segments, $index, DEFAULT_MODEL);
      $index++;
      $segments[$index] = Arrays::get($segments, $index, DEFAULT_VIEW);
    }

    return $segments;
  }

  public static function get_model_name() {
    $segments = self::segments();
    if (USE_NAMESPACE) {
      return $segments[0].'/'.$segments[1];
    } else {
      return $segments[0];
    }
  }

  public static function get_view_name() {
    $segments = self::segments();
    return $segments[USE_NAMESPACE ? 2 : 1];
  }

  public static function get_action() {
    return isset($_REQUEST['_action']) ? $_REQUEST['_action'] : strtolower($_SERVER['REQUEST_METHOD']);
  }

  public static function get_arguments() {
    $segments = self::segments();
    return array_slice($segments, USE_NAMESPACE ? 3 : 2);
  }

  public static function current_path() {
    return $_SERVER['REQUEST_URI'];
  }

  // relative path starting from base_path()
  public static function site_path() {
    $segments = self::segments();
    return implode('/', $segments);
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
