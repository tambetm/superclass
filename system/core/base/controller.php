<?php
namespace core\base;

use helpers\Session;

class Controller implements \interfaces\Controller {

  var $context;

  function __construct($context) {
    $this->context = $context;
    Session::start();
  }

  function __call($name, $arguments) {
    // determine view class, model class and database table
    $view_class = $this->context->get_view_class();
    $model_class = $this->context->get_model_class();
    $table = $this->context->get_database_table();

    // instantiate model and view
    $model = new $model_class($table);
    $view = new $view_class($model);

    // call appropriate method depending on request method
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'GET':
        $view->get();
        break;

      case 'POST':
        $view->post();
        break;

      default:
        trigger_error("Request method ".$_SERVER['REQUEST_METHOD']." unsupported", E_USER_ERROR);
        exit;
    }

    // instantiate layout with view
    $layout_class = $this->context->get_layout_class();
    $layout = new $layout_class($view);

    $method = $this->context->get_method();
    if (method_exists($layout, $method)) {
      // render layout
      call_user_func_array(array($layout, $method), $arguments);  
    } elseif (method_exists($view, $method)) {
      // render only view
      call_user_func_array(array($view, $method), $arguments);  
    } else {
      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
      throw new \BadMethodCallException("Method '$method' doesn't exist in view or layout");
    }
  }
}
