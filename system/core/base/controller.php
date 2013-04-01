<?php
namespace core\base;

use core\Resolver as _Resolver;
use helpers\URL;
use helpers\Config;
use helpers\Response;
use helpers\Arrays;

class Controller implements \core\interfaces\Controller {

  protected $config;

  public function __construct($model_name, $view_name, $action) {
    $this->model_name = $model_name;
    $this->view_name = $view_name;
    $this->action = $action;

    // load configuration for controller
    Config::load($this->config, CONTROLLER_NAMESPACE.DIRECTORY_SEPARATOR."_$model_name.php");
    session_start();
  }

  protected function invoke($model_name, $view_name, $action = DEFAULT_ACTION, $layout_name = DEFAULT_LAYOUT) {
    // resolve model, view and layout names
    $model_class = _Resolver::get_model_class($model_name);
    $view_class = _Resolver::get_view_class($model_name, $view_name);
    $layout_class = _Resolver::get_layout_class($layout_name);

    // instantiate model and view
    $model = new $model_class($model_name);
    $view = new $view_class($model, $view_name);

    // call view method
    $view->$action();

    // instantiate layout with view
    $layout = new $layout_class($view);
    $layout->render();
    exit;
  }

  public function __call($name, $arguments) {
    if (isset($this->config[$name])) {
      // if configuration for this method exists, invoke model and view
      $config = $this->config[$name];
      $this->invoke(
        Arrays::get($config, 'model', $this->model_name), 
        Arrays::get($config, 'view', $this->view_name),
        Arrays::get($config, 'action', $this->action),
        Arrays::get($config, 'layout', DEFAULT_LAYOUT)
      );
    } else {
      Response::code(404);
      throw new \BadMethodCallException("Method '$name' doesn't exist in controller");
    }
  }
}
