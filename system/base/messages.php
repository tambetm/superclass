<?php
namespace base;

use core\Session;

class Messages {

  static public $headings;
  static protected $session;

  static public function init() {
    self::$session = Session::instance();
  }

  static public function message($type, $title, $items = null) {
    // make up a message
    $message = array(
      'type' => $type,
      'title' => $title,
    );
    if (!is_null($items)){
      if (is_array($items)) {
        $message['items'] = $items;
      } else {
        $message['text'] = $items;
      }
    }

    // add message to list
    $messages = self::$session->messages;
    $messages[] = $message;
    self::$session->messages = $messages;
  }

  static public function messages() {
    // return messages and purge them from session
    $messages = self::$session->messages;
    unset(self::$session->messages);
    return $messages;
  }

  static public function error($message, $items = null) {
    self::message('error', $message, $items);
  }

  static public function alert($message, $items = null) {
    self::message('alert', $message, $items);
  }

  static public function info($message, $items = null) {
    self::message('info', $message, $items);
  }

  static public function success($message, $items = null) {
    self::message('success', $message, $items);
  }

  static public function log($message, $items = null) {
    self::message('log', $message, $items);
  }

  static public function debug($message, $items = null) {
    self::message('debug', $message, $items);
  }
}

Messages::init();
