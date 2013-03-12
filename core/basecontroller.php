<?php

class core_BaseController {

  var $context;

  function __construct($context) {
    $this->context = $context;
  }

  function __call($name, $arguments) {
    // determine view class
    $view_class = $this->context->get_view_class();
    if (!$view_class) {
      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
      trigger_error("View doesn't exist for ".$this->context->url, E_USER_ERROR);
      exit;
    }

    // determine model class and database table
    $model_class = $this->context->get_model_class();
    $table = $this->context->get_database_table();

    // instantiate model and view
    $model = new $model_class($table);
    $view = new $view_class($model);

    // instantiate page with view rendered for default region
    $page_class = $this->context->get_page_class();
    $page = new $page_class(array(DEFAULT_REGION => $view));

    // render page
    call_user_func_array(array($page, 'render'), $arguments);  
  }
}
