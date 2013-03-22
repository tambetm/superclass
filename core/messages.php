<?php
namespace core;

class Messages {
  const MESSAGES_SESSION_NAME = 'core\Messages::messages';

  static public function message($text, $type = 'error', $important = true) {
    if (isset($_SESSION[self::MESSAGES_SESSION_NAME])) {
      $messages = $_SESSION[self::MESSAGES_SESSION_NAME];
    } else {
      $messages = array();
    }
    $messages[] = array(
      'type' => $type,
      'text' => $text,
      'important' => $important,
    );
    $_SESSION[self::MESSAGES_SESSION_NAME] = $messages;
  }

  static public function messages() {
    if (isset($_SESSION[self::MESSAGES_SESSION_NAME])) {
      $messages = $_SESSION[self::MESSAGES_SESSION_NAME];
    } else {
      $messages = null;
    }
    unset($_SESSION[self::MESSAGES_SESSION_NAME]);
    return $messages;
  }

  static public function error($message, $important = false) {
    self::message($message, 'error', $important);
  }

  static public function alert($message, $important = false) {
    self::message($message, 'alert', $important);
  }

  static public function info($message, $important = false) {
    self::message($message, 'info', $important);
  }

  static public function success($message, $important = false) {
    self::message($message, 'success', $important);
  }

  static public function log($message, $important = false) {
    self::message($message, 'log', $important);
  }

  static public function debug($message, $important = false) {
    self::message($message, 'debug', $important);
  }

/*
  static public function __callStatic($name, $arguments) {
    array_splice($arguments, 1, 0, $name);
    call_user_func_array('self::message', $arguments);
  }
*/
}