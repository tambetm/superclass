<?php

class core_BaseController implements interfaces_Controller {

  var $context;

  function __construct($context) {
    $this->context = $context;
  }

  function __call($name, $arguments) {
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'GET':
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

        // instantiate layout with view rendered for default region
        $layout_class = $this->context->get_layout_class();
        $layout = new $layout_class(array(DEFAULT_REGION => $view));

        // render layout
        call_user_func_array(array($layout, 'render'), $arguments);  
        break;

      case 'POST':
        // determine action class
        $action_class = $this->context->get_action_class();
        if (!$action_class) {
          header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
          trigger_error("Action '$action_class' doesn't exist for ".$this->context->url, E_USER_ERROR);
          exit;
        }

        // determine model class and database table
        $model_class = $this->context->get_model_class();
        $table = $this->context->get_database_table();

        // instantiate model and action
        $model = new $model_class($table);
        $action = new $action_class($model);

        // process action. action always ends with redirect, so no need for view and layout here.
        $model->begin();
        call_user_func_array(array($action, 'process'), $arguments);
        $model->commit();
        break;

      default:
        trigger_error("Request method ".$_SERVER['REQUEST_METHOD']." unsupported", E_USER_ERROR);
        exit;
    }
  }
}
