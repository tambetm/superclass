<?php
namespace core\base;

use helpers\Messages;
use helpers\Config;

class ErrorHandler {

  protected $config;
  protected $cwd;

  public function __construct() {
    Config::load($this->config, 'config/error_handler.php');
    $this->cwd = getcwd();
  }
 
  static public $error_levels = array
  (
    E_ERROR => 'PHP Error',
    E_WARNING => 'PHP Warning',
    E_PARSE => 'PHP Parse',
    E_NOTICE => 'PHP Notice',
    E_CORE_ERROR => 'PHP Core Error',
    E_CORE_WARNING => 'PHP Core Warning',
    E_COMPILE_ERROR => 'PHP Compile Error',
    E_COMPILE_WARNING => 'PHP Compile Warning',
    E_USER_ERROR => 'PHP User Error',
    E_USER_WARNING => 'PHP User Warning',
    E_USER_NOTICE => 'PHP User Notice',
    E_STRICT => 'PHP Strict',
    E_RECOVERABLE_ERROR => 'PHP Recoverable Error',
    E_DEPRECATED => 'PHP Deprecated',
    E_USER_DEPRECATED => 'PHP User Deprecated',
    //E_ALL => ???,
  );

  public function handle_exception($exception) {
    $errno = $exception->getCode();
    // if exception, then report it at E_ERROR level
    if (!$errno) $errno = E_ERROR;
    // check that errors haven't been suppressed with @ modifier
    if ($errno & error_reporting()) {
      $error = $exception->getMessage()." in ".$exception->getFile()." on line ".$exception->getLine()."\n".$exception->getTraceAsString();

      // log error
      error_log($error);

      // when error e-mail set, send e-mail
      if (isset($this->config['error_email'])) {
        error_log(date('[d-M-Y H:i:s] ').$error, 1, $this->config['error_email']);
      }

      // remember message to show on next page view
      Messages::log($error);

      // when at least warning level error, show error reporting page
      if ($errno & (E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR)) {
        // clean output buffer
        while (ob_get_level() > 0) ob_end_clean();
        // don't send 500 header, because then IE doesn't show our content
        //Response::code(500);
        $error_layout_class = ERROR_LAYOUT_CLASS;
        $layout = new $error_layout_class($exception);
        $layout->render();
        exit;
      }
    }
  }

  public function handle_error($errno, $errstr, $errfile, $errline) {
    $exception = new \ErrorException(self::$error_levels[$errno].': '.$errstr, $errno, 0, $errfile, $errline);
    if ($errno & (E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR)) {
      // throw exception, so that it can be caught
      throw $exception;
    } else {
      // call hande_exception() directly instead of throwing an exception,
      // because otherwise execution wouldn't continue for notice level errors.
      $this->handle_exception($exception);
    }
  }

  public function handle_fatal_error() {
    $error = @error_get_last();
    // in Apache current directory might be off when fatal error occurs.
    // restore it after we have retrieved the last error, so errors changing 
    // directory won't give us false alarms.
    chdir($this->cwd);
    if (!is_null($error) && $error['type'] == E_ERROR)
    {
      $this->handle_error($error['type'], $error['message'], $error['file'], $error['line']);
    }
  }
}
