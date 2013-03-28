<?php
namespace core\interfaces;

interface Translator {
  static public function init($lang);
  static public function _();
  static public function _n();
}
