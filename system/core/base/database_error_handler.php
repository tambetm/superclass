<?php
namespace core\base;

use core\ErrorHandler as _ErrorHandler;
use helpers\Messages;

class DatabaseErrorHandler extends _ErrorHandler {

  // handle only errors, because can't continue execution with exceptions
  public function handle_error($errno, $errstr, $errfile, $errline) {
    include('config/database_error_handler.php');
    if (isset($config['rules']) and is_array($config['rules'])) {
      foreach($config['rules'] as $regexp => $message) {
        if (preg_match('/'.$regexp.'/', $errstr, $matches)) {
          $matches[0] = $message;
          $message = call_user_func_array('sprintf', $matches);
          Messages::error_item($message);
          return true;
        }
      }
    }
    return parent::handle_error($errno, $errstr, $errfile, $errline);
  }
}
