<?php
namespace core;

class Log {
  static public function error($message) {
    trigger_error($message, E_USER_ERROR);
  }

  static public function warning($message) {
    trigger_error($message, E_USER_WARNING);
  }

  static public function notice($message) {
    trigger_error($message, E_USER_NOTICE);
  }
}
