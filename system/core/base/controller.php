<?php
namespace core\base;

use helpers\Session;
use helpers\Request;

class Controller implements \interfaces\Controller {

  var $context;

  function __construct($context) {
    $this->context = $context;
    session_start();
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
    $request_method = Request::method();
    switch ($request_method) {
      case 'GET':
        $view->get($_GET);
        break;

      case 'POST':
        $view->post($_POST);
        break;

      default:
        throw new \BadMethodCallException("Request method '$request_method' unsupported");
    }

    // instantiate layout with view
    $layout_class = $this->context->get_layout_class();
    $layout = new $layout_class($view);

    $method = $this->context->get_render_method();
    if (method_exists($layout, $method)) {
      // render layout
      call_user_func_array(array($layout, $method), $arguments);  
    } elseif (method_exists($view, $method)) {
      // render only view
      call_user_func_array(array($view, $method), $arguments);  
    } else {
      Response::code(404);
      throw new \BadMethodCallException("Method '$method' doesn't exist in view or layout");
    }
  }
}
