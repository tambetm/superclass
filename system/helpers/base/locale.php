<?php
namespace helpers\base;

class Locale {

  static public function get_current() {
    return locale_get_default();
  }

  static public function get_conventions() {
    return localeconv();
  }

  static public function get_primary_language() {
    return locale_get_primary_language(self::get_current());
  }

  static public function get_charset() {
    return ini_get('default_charset');
  }
}
