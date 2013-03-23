<?php
namespace core;

use core\URL;
use core\String;

class Context {
  
  protected $url;
  protected $segments;
  protected $action;
  protected $method;
  protected $classbase;

  public function __construct($url) {
    $this->url = $url;

    // trim slashes and convert url to lowercase, just in case.
    $url = strtolower(trim($url, '/'));

    // extract view method for partial page rendering
    $pos = strpos($url, ';');
    if ($pos !== false) {
      $this->method = substr($url, $pos + 1);
      $url = substr($url, 0, $pos);
    } else {
      $this->method = DEFAULT_METHOD;
    }

    // split url into segments, ignoring empty segments.
    $this->segments = array_filter(explode('/', $url));

    // last part is action (view name or controller method)
    if (count($this->segments) > 1) {
      $this->action = array_pop($this->segments);
    } else {
      $this->action = DEFAULT_ACTION;
    }

    // everything remaining is resource (table and schema, or alternatively controller and namespace)
    if (count($this->segments) > 0) {
      // camelcase last element
      $last = count($this->segments) - 1;
      $this->segments[$last] = String::camelcase($this->segments[$last]);
    } else {
      // use default resource
      $this->segments[] = String::camelcase(DEFAULT_RESOURCE);
    }

    $this->classbase = implode(NAMESPACE_SEPARATOR, $this->segments);
  }

  public function get_controller_class() {
    $controller_class = CONTROLLER_NAMESPACE.NAMESPACE_SEPARATOR.$this->classbase;
    if (class_exists($controller_class)) {
      return $controller_class;
    } else {
      return DEFAULT_CONTROLLER_CLASS;
    }
  }

  public function get_controller_method() {
    return $this->action;
  }

  public function get_view_class() {
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$this->classbase.String::camelcase($this->action);
    if (class_exists($view_class)) {
      return $view_class;
    }
    return VIEW_NAMESPACE.NAMESPACE_SEPARATOR.String::camelcase($this->action);
  }

  public function get_model_class() {
    $model_class = MODEL_NAMESPACE.NAMESPACE_SEPARATOR.$this->classbase;
    if (class_exists($model_class)) {
      return $model_class;
    } else {
      return DEFAULT_MODEL_CLASS;
    }
  }

  public function get_database_table() {
    return str_replace(NAMESPACE_SEPARATOR, SCHEMA_SEPARATOR ,String::underscore($this->classbase));
  }

  public function get_layout_class() {
    return DEFAULT_LAYOUT_CLASS;
  }

  public function get_method() {
    return $this->method;
  }
}
