<?php
namespace helpers\base;

class String {

  static public function contains($haystack, $needle, $case = true) {
    if ($case) {
      return strpos($haystack, $needle) !== false;
    } else {
      return stripos($haystack, $needle) !== false;
    }
  }

  static public function starts_with($haystack, $needle, $case = true) {
    if ($case) {
      return strpos($haystack, $needle) === 0;
    } else {
      return stripos($haystack, $needle) === 0;
    }
  }

  static public function ends_with($haystack, $needle) {
    $pos = strlen($haystack) - strlen($needle);
    if ($case) {
      return strrpos($haystack, $needle, 0) === $pos;
    } else {
      return strripos($haystack, $needle, 0) === $pos;
    }
  }

  public static function camelcase($word) {
    return preg_replace_callback('/(^|_| +)([a-z])/', function ($matches) {return strtoupper($matches[2]);}, strtolower($word));
  }

  public static function underscore($word) {
    return strtolower(preg_replace_callback('/([a-z])([A-Z])/', function ($matches) {return "$matches[1]_$matches[2]";}, $word));
  }

  public static function human($word) {
    return ucfirst(str_replace('_', ' ', $word));
  }
}
