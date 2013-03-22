<?php
namespace core;

use core\URL;
use core\String;

class Context {
  
  public $url;
  protected $segments;
  protected $method;
  protected $class;

  public function __construct($url = null) {
    if (is_null($url)) {
      $url = URL::current_url();
    }
    $this->url = $url;
    $this->segments = array_filter(explode('/', strtolower(trim($url, '/'))));

    if (count($this->segments) > 0) {
      // uppercase first letter of last element
      $last = count($this->segments) - 1;
      $this->segments[$last] = String::camelize($this->segments[$last]);
    } else {
      $this->segments[] = DEFAULT_CONTROLLER;
    }

    if (count($this->segments) > 1) {
      $this->method = array_pop($this->segments);
    } else {
      $this->method = DEFAULT_METHOD;
    }

    $this->class = implode(NAMESPACE_SEPARATOR, $this->segments);
  }

  public function get_controller_class() {
    $controller_class = CONTROLLER_NAMESPACE.NAMESPACE_SEPARATOR.$this->class;
    if (class_exists($controller_class)) {
      return $controller_class;
    } else {
      return DEFAULT_CONTROLLER_CLASS;
    }
  }

  public function get_controller_method() {
    return $this->method;
  }

  public function get_view_class() {
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$this->class.String::camelize($this->method);
    if (class_exists($view_class)) {
      return $view_class;
    }
    return VIEW_NAMESPACE.NAMESPACE_SEPARATOR.String::camelize($this->method);
  }

  public function get_action_class() {
    $action_class = ACTION_NAMESPACE.NAMESPACE_SEPARATOR.$this->class.String::camelize($this->method);
    if (class_exists($action_class)) {
      return $action_class;
    }
    return ACTION_NAMESPACE.NAMESPACE_SEPARATOR.String::camelize($this->method);
  }

  public function get_model_class() {
    $model_class = MODEL_NAMESPACE.NAMESPACE_SEPARATOR.$this->class;
    if (class_exists($model_class)) {
      return $model_class;
    } else {
      return DEFAULT_MODEL_CLASS;
    }
  }

  public function get_database_table() {
    return str_replace(NAMESPACE_SEPARATOR, SCHEMA_SEPARATOR ,String::uncamelize($this->class));
  }

  public function get_layout_class() {
    return DEFAULT_LAYOUT_CLASS;
  }

}
