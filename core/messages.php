<?php
namespace core;

use core\Session;

class Messages {

  static public $headings;
  static protected $session;

  static public function init() {
    self::$session = Session::instance();
    self::$headings = array(
      'error' => _('Error'),
      'alert' => _('Alert'),
      'info' => _('Info'),
      'success' => _('Success'),
      'log' => _('Log'),
      'debug' => _('Debug'),
    );
  }

  static public function message($type, $title, $text = null) {
    // make up a message
    $message = array(
      'type' => $type,
      'title' => $title,
      'text' => $text,
    );

    // add items, if they exist
    $message_items = self::$session->message_items;
    if (isset($message_items[$type])) {
      $message['items'] = $message_items[$type];
      unset($message_items[$type]);
      self::$session->message_items = $message_items;
    }

    // add message to list
    $messages = self::$session->messages;
    $messages[] = $message;
    self::$session->messages = $messages;
  }

  static public function item($type, $message) {
    // add item to list
    $message_items = self::$session->message_items;
    $message_items[$type][] = $message;
    self::$session->message_items = $message_items;
  }

  static public function messages() {
    // pick up remaining items
    $message_items = self::$session->message_items;
    if (is_array($message_items)) {
      foreach($message_items as $type => $items) {
        self::message($type, self::$headings[$type]);
      }
    }

    // return messages and purge them from session
    $messages = self::$session->messages;
    unset(self::$session->messages);
    return $messages;
  }

  static public function __callStatic($name, $arguments) {
    // if ends with _item
    if (strpos($name, '_item') === strlen($name) - 5) {
      array_unshift($arguments, substr($name, 0, strlen($name) - 5));
      call_user_func_array('self::item', $arguments);
    } else {
      array_unshift($arguments, $name);
      call_user_func_array('self::message', $arguments);
    }
  }
}

Messages::init();
