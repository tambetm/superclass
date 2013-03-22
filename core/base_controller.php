<?php
namespace core;

use interfaces\Controller;

class BaseController implements Controller {

  var $context;

  function __construct($context) {
    $this->context = $context;
  }

  function __call($name, $arguments) {
    // determine view class
    $view_class = $this->context->get_view_class();
    if (!$view_class) {
      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
      trigger_error("View '$view_class' doesn't exist for ".$this->context->url, E_USER_ERROR);
      exit;
    }

    // determine model class and database table
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

    // render layout
    call_user_func_array(array($layout, 'render'), $arguments);  
  }
}
