<?php
namespace helpers\base;

class String {

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