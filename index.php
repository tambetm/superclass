<?php
define('NAMESPACE_SEPARATOR', '_');

// set up class autoloading
function __autoload($class_name) {
  // file name is class name in lowercase, underscores replaced with directory separators
  $filename = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, strtolower($class_name)) . '.php';
  // don't report error when file read fails. we'll get error anyway for non-existent class.
  @include_once($filename);
}

// set up error handling.
$errorhandler = new core_ErrorHandler();
set_error_handler(array($errorhandler, 'handle_error'));
set_exception_handler(array($errorhandler, 'handle_exception'));
register_shutdown_function(array($errorhandler, 'handle_fatal_error'));

// extract class name and method name from URL path
$url = $_SERVER['PATH_INFO'];
$context = new core_Context($url);
$class = $context->get_controller_class();
$method = $context->get_controller_method();

// instantiate and call controller object
$obj = new $class($context);
$obj->$method();
