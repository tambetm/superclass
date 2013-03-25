<?php
namespace helpers\base;

use helpers\Session as _Session;

class Messages {

  const SESSION_MESSAGES_NAME = 'helpers\Messages::messages';
  const SESSION_ITEMS_NAME = 'helpers\Messages::items';

  static public $headings;
  
  public function init() {
    self::$headings = array(
      'error' => _('Error'),
      'alert' => _('Alert'),
      'success' => _('Success'),
      'info' => _('Info'),
      'log' => _('Log'),
      'debug' => _('Debug'),
    );
  }

  static protected $prefix = '';

  static public function message($type, $title, $text = null) {
    // make up a message
    $message = array(
      'type' => $type,
      'title' => $title,
      'text' => $text,
    );

    // add message items
    $items = _Session::get(self::SESSION_ITEMS_NAME, array());
    if (isset($items[$type])) {
      $message['items'] = $items[$type];
      unset($items[$type]);
    }
    _Session::set(self::SESSION_ITEMS_NAME, $items);

    // add message to list
    $messages = _Session::get(self::SESSION_MESSAGES_NAME, array());
    $messages[] = $message;
    _Session::set(self::SESSION_MESSAGES_NAME, $messages);
  }

  static public function item($type, $text) {
    $items = _Session::get(self::SESSION_ITEMS_NAME, array());
    $items[$type][] = $text;
    _Session::set(self::SESSION_ITEMS_NAME, $items);
  }

  static public function messages() {
    // gather non-used items and create a message for them
    $items = _Session::get(self::SESSION_ITEMS_NAME, array());
    if (is_array($items)) {
      foreach($items as $type => $message_items) {
        self::message($type, self::$headings[$type]);
      }
    }
     
    // purge messages from session and return them
    $messages = _Session::get(self::SESSION_MESSAGES_NAME);
    _Session::remove(self::SESSION_MESSAGES_NAME);
    return $messages;
  }

  static public function item_prefix($prefix) {
    self::$prefix = $prefix;
  }

  static public function error($message, $text = null) {
    self::message('error', $message, $text);
  }

  static public function error_item($message) {
    self::item('error', self::$prefix.$message);
  }

  static public function alert($message, $text = null) {
    self::message('alert', $message, $text);
  }

  static public function alert_item($message) {
    self::item('alert', self::$prefix.$message);
  }

  static public function info($message, $text = null) {
    self::message('info', $message, $text);
  }

  static public function info_item($message) {
    self::item('info', self::$prefix.$message);
  }

  static public function success($message, $text = null) {
    self::message('success', $message, $text);
  }

  static public function success_item($message) {
    self::item('success', self::$prefix.$message);
  }

  static public function log($message) {
    // show debug information only in development system
    if (ini_get('display_errors')) {
      self::item('log', $message);
    }
  }

  static public function debug($message) {
    // show debug information only in development system
    if (ini_get('display_errors')) {
      self::item('debug', $message);
    }
  }
}
