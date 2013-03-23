<?php
namespace helpers\base;

use helpers\Session as _Session;

class Messages {

  const SESSION_MESSAGES_NAME = 'messages';

  static public function message($type, $title, $items = null) {
    // make up a message
    $message = array(
      'type' => $type,
      'title' => $title,
    );
    if (!is_null($items)){
      // if third parameter is array, it is items, otherwise text
      if (is_array($items)) {
        $message['items'] = $items;
      } else {
        $message['text'] = $items;
      }
    }

    // add message to list
    $messages = _Session::get(self::SESSION_MESSAGES_NAME);
    $messages[] = $message;
    _Session::set(self::SESSION_MESSAGES_NAME, $messages);
  }

  static public function messages() {
    // return messages and purge them from session
    $messages = _Session::get(self::SESSION_MESSAGES_NAME);
    _Session::remove(self::SESSION_MESSAGES_NAME);
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
