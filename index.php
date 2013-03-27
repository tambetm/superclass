<?php
// include config
require_once('config/php.php');
require_once('config/framework.php');

// HACK: autoloader itself uses String::underscore()
require_once('helpers/base/string.php');
require_once('helpers/string.php');

use helpers\String;
use core\ErrorHandler;
use core\Resolver;
use helpers\Url;

// set up class autoloading
function __autoload($class_name) {
  // file name is class name in lowercase, underscores replaced with directory separators
  $filename = String::underscore(str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name)) . '.php';
  // check for class file and include it
  $filepath = stream_resolve_include_path($filename);
  if ($filepath !== false) {
    // include with absolute path, this hopefully saves us second scan of include_path
    include_once $filepath;
  }
}

// set up error handling.
$error_handler_class = ERROR_HANDLER_CLASS;
$error_handler = new $error_handler_class();
set_error_handler(array($error_handler, 'handle_error'));
set_exception_handler(array($error_handler, 'handle_exception'));
register_shutdown_function(array($error_handler, 'handle_fatal_error'));

// extract class name and method name from URL path
$class = Resolver::get_controller_class(URL::get_resource());
$method = Resolver::get_controller_method(URL::get_action());

// instantiate and call controller object
$obj = new $class();
$obj->$method();
