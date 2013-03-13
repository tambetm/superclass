<?php

class core_ErrorHandler {

  var $config;
  var $cwd;

  function __construct() {
    include('config/errorhandler.php');
    $this->config = $config;
    $this->cwd = getcwd();
  }
 
  var $error_levels = array
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

  function handle_exception($exception, $errno = E_ERROR) {
    // check that errors haven't been suppressed with @ modifier
    if ($errno & error_reporting()) {
      $error = $exception->getMessage()."\n".$exception->getTraceAsString();

      // log error
      error_log($error);

      // when error e-mail set, send e-mail
      if (isset($this->config['error_email'])) {
        error_log(date('[d-M-Y H:i:s] ').$error, 1, $this->config['error_email']);
      }

      // when at least warning level error, show error reporting page
      if ($errno & (E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING)) {
        // clean output buffer
        while (ob_get_level() > 0) ob_end_clean();
        // don't send 500 header, because then IE doesn't show our content
        //header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $layout = new layouts_Error($exception);
        $layout->render();
        exit;
      } else if (ini_get('display_errors')) { // display errors, if enabled
        echo nl2br($error);
      }
    }
  }

  function handle_error($errno, $errstr, $errfile, $errline) {
    // create exception based on the error
    $exception = new ErrorException($this->error_levels[$errno].': '.$errstr, $errno, 0, $errfile, $errline);
    $this->handle_exception($exception, $errno);
  }

  function handle_fatal_error() {
    $error = @error_get_last();
    if (!is_null($error) && $error['type'] == E_ERROR)
    {
      $this->handle_error($error['type'], $error['message'], $error['file'], $error['line']);
    }
  }
}
