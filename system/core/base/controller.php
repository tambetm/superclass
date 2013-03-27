<?php
namespace core\base;

use core\Resolver as _Resolver;
use helpers\URL;

class Controller implements \interfaces\Controller {

  public function __construct() {
    session_start();
  }

  protected function invoke($resource, $action, $method = DEFAULT_SUBACTION, $layout_name = DEFAULT_LAYOUT) {
    $table = _Resolver::get_database_table($resource);
    $model_class = _Resolver::get_model_class($resource);
    $view_class = _Resolver::get_view_class($resource, $action);
    $layout_class = _Resolver::get_layout_class($layout_name);

    // instantiate model and view
    $model = new $model_class($table);
    $view = new $view_class($model);

    // call appropriate view method depending on request method
    $request_method = $_SERVER['REQUEST_METHOD'];
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
    $layout = new $layout_class($view);
    // call appropriate method, check layout first
    if (method_exists($layout, $method)) {
      // render layout
      call_user_func(array($layout, $method));  
    } elseif (method_exists($view, $method)) {
      // render only view
      call_user_func(array($view, $method));  
    } else {
      Response::code(404);
      throw new \BadMethodCallException("Method '$method' doesn't exist in view or layout");
    }
  }

  public function __call($name, $arguments) {
    // determine model, view and method from URL
    $this->invoke(URL::get_resource(), URL::get_action(), URL::get_subaction());
  }
}
