<?php
namespace core;

class String {

  public static function camelize($word) {
    return preg_replace_callback('/(^|_)([a-z])/', function ($matches) {return strtoupper($matches[2]);}, $word);
  }

  public static function uncamelize($word) {
    return strtolower(preg_replace_callback('/([a-z])([A-Z])/', function ($matches) {return "$matches[1]_$matches[2]";}, $word));
  }
}