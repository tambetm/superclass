<?php

// include config
require_once('config/php.php');
require_once('config/framework.php');
require_once('core/string.php'); // HACK

use core\String;

// set up class autoloading
function __autoload($class_name) {
  // file name is class name in lowercase, underscores replaced with directory separators
  $filename = String::uncamelize(str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name)) . '.php';
  // check for class file and include it
	$paths = explode(PATH_SEPARATOR, get_include_path());
	foreach ($paths as $path) {
		if (is_readable($path.DIRECTORY_SEPARATOR.$filename)) {
      include_once $filename;
    }
	}
}

// set up error handling.
$error_handler_class = ERROR_HANDLER_CLASS; 
$errorhandler = new $error_handler_class();
set_error_handler(array($errorhandler, 'handle_error'));
set_exception_handler(array($errorhandler, 'handle_exception'));
register_shutdown_function(array($errorhandler, 'handle_fatal_error'));

// extract class name and method name from URL path
$context_class = CONTEXT_CLASS;
$context = new $context_class();
$class = $context->get_controller_class();
$method = $context->get_controller_method();

// instantiate and call controller object
$obj = new $class($context);
$obj->$method();
