<?php
namespace core\base;

use core\Resolver as _Resolver;
use helpers\URL;
use helpers\Config;
use helpers\Response;
use helpers\Arrays;

class Controller implements \core\interfaces\Controller {

  protected $config;

  public function __construct($name) {
    // load configuration for controller
    Config::load($this->config, CONTROLLER_NAMESPACE.DIRECTORY_SEPARATOR."_$name.php");
    session_start();
  }

  protected function invoke($resource, $action, $method = DEFAULT_METHOD, $layout_name = DEFAULT_LAYOUT) {
    // resolve model, view and layout names
    $model_class = _Resolver::get_model_class($resource);
    $view_class = _Resolver::get_view_class($resource, $action);
    $layout_class = _Resolver::get_layout_class($layout_name);

    // instantiate model and view
    $model = new $model_class($resource);
    $view = new $view_class($model, $action);

    // call view method
    $view->$method();

    // instantiate layout with view
    $layout = new $layout_class($view);
    $layout->render();
    exit;
  }

  public function __call($name, $arguments) {
    if (isset($this->config[$name])) {
      // if configuration for this method exists, invoke model, view and controller
      $config = $this->config[$name];
      $this->invoke(
        Arrays::get($config, 'model', URL::get_resource()), 
        Arrays::get($config, 'view', URL::get_action()),
        Arrays::get($config, 'method', URL::get_method()),
        Arrays::get($config, 'layout', DEFAULT_LAYOUT));
    } else {
      Response::code(404);
      throw new \BadMethodCallException("Method '$name' doesn't exist in controller");
    }
  }
}
