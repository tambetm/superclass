<?php

define('DEFAULT_RESOURCE', 'Default');
define('DEFAULT_ACTION', 'index');
define('CONTROLLER_NAMESPACE', 'controllers');
define('MODEL_NAMESPACE', 'models');
define('VIEW_NAMESPACE', 'views');
define('SCHEMA_SEPARATOR', '.');
define('BASE_CONTROLLER', 'core_BaseController');
define('BASE_MODEL', 'core_BaseModel');

class core_Context {
  
  var $url;
  var $segments;
  var $action;
  var $resource;

  function __construct($url) {
    $this->url = $url;
    $this->segments = explode('/', strtolower(trim($url, '/')));

    if (count($this->segments) > 0) {
      $last = count($this->segments) - 1;
      $this->segments[$last] = ucfirst($this->segments[$last]);
    } else {
      $this->segments[] = DEFAULT_RESOURCE;
    }

    if (count($this->segments) > 1) {
      $this->action = array_pop($this->segments);
    } else {
      $this->action = DEFAULT_ACTION;
    }

    $this->resource = implode(NAMESPACE_SEPARATOR, $this->segments);
  }

  function get_controller_class() {
    $controller_class = CONTROLLER_NAMESPACE.NAMESPACE_SEPARATOR.$this->resource;
    if (class_exists($controller_class)) {
      return $controller_class;
    } else {
      return BASE_CONTROLLER;
    }
  }

  function get_controller_method() {
    return $this->action;
  }

  function get_view_class() {
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$this->resource.ucfirst($this->action);
    if (class_exists($view_class)) {
      return $view_class;
    }
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.ucfirst($this->action);
    if (class_exists($view_class)) {
      return $view_class;
    } else {
      return null;
    }
  }

  function get_model_class() {
    $model_class = MODEL_NAMESPACE.NAMESPACE_SEPARATOR.$this->resource;
    if (class_exists($model_class)) {
      return $model_class;
    } else {
      return BASE_MODEL;
    }
  }

  function get_database_table() {
    return str_replace(NAMESPACE_SEPARATOR, SCHEMA_SEPARATOR ,strtolower($this->resource));
  }
}
