<?php
namespace helpers\base;

use helpers\Session as _Session;

class Messages {

  const SESSION_MESSAGES_NAME = 'helpers\Messages::messages';

  static protected $row = null;
  static protected $prefix = '';
  static protected $field = null;
  static protected $items = array();
  static protected $field_items = array();
  static protected $row_statuses = array();

  static public function message($type, $title, $text = null) {
    // make up a message
    $message = array(
      'type' => $type,
      'title' => $title,
    );

    // add text, if set
    if (!is_null($text)) {
      $message['text'] = $text;
    }

    // add message items
    if (isset(self::$items[$type])) {
      $message['items'] = self::$items[$type];
      // purge items of this type
      unset(self::$items[$type]);
    }

    // add message to list
    $messages = _Session::get(self::SESSION_MESSAGES_NAME, array());
    $messages[] = $message;
    _Session::set(self::SESSION_MESSAGES_NAME, $messages);
  }

  static public function item($type, $text) {
    $item = array(
      'type' => $type,
      'text' => $text,
    );

    // set contextual fields
    if (!is_null(self::$row)) {
      $item['row'] = self::$row;
      $item['prefix'] = self::$prefix;
      // row status is status of last message
      self::$row_statuses[self::$row] = $type;
      if (!is_null(self::$field)) {
        $item['field'] = self::$field;
        // record field item
        self::$field_items[self::$row][self::$field] = $item;
      }
    }
    self::$items[$type][] = $item;
  }

  static public function messages() {
    // purge messages from session and return them
    $messages = _Session::get(self::SESSION_MESSAGES_NAME);
    _Session::remove(self::SESSION_MESSAGES_NAME);
    return $messages;
  }

  static public function row_status($row) {
    return isset(self::$row_statuses[$row]) ? self::$row_statuses[$row] : '';
  }

  static public function field_status($row, $field) {
    return isset(self::$field_items[$row][$field]['type']) ? self::$field_items[$row][$field]['type'] : '';
  }

  static public function field_message($row, $field) {
    return isset(self::$field_items[$row][$field]['text']) ? self::$field_items[$row][$field]['text'] : '';
  }

  static public function item_row($row, $prefix = '') {
    self::$row = $row;
    self::$prefix = $prefix;
  }

  static public function item_field($field) {
    self::$field = $field;
  }

  static public function error($message, $text = null) {
    self::message('error', $message, $text);
  }

  static public function error_item($message) {
    self::item('error', $message);
  }

  static public function alert($message, $text = null) {
    self::message('alert', $message, $text);
  }

  static public function alert_item($message) {
    self::item('alert', $message);
  }

  static public function info($message, $text = null) {
    self::message('info', $message, $text);
  }

  static public function info_item($message) {
    self::item('info', $message);
  }

  static public function success($message, $text = null) {
    self::message('success', $message, $text);
  }

  static public function success_item($message) {
    self::item('success', $message);
  }

  static public function log($message) {
    // show debug information only in development system
    if (ini_get('display_errors')) {
      self::message('log', null, $message);
    }
  }

  static public function debug($message) {
    // show debug information only in development system
    if (ini_get('display_errors')) {
      self::message('debug', null, $message);
    }
  }
}
