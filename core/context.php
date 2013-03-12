<?php

class core_Context {
  
  public $url;
  protected $segments;
  protected $method;
  protected $class;

  public function __construct($url = null) {
    if (is_null($url)) {
      $url = self::current_url();
    }
    $this->url = $url;
    $this->segments = array_filter(explode('/', strtolower(trim($url, '/'))));

    if (count($this->segments) > 0) {
      // uppercase first letter of last element
      $last = count($this->segments) - 1;
      $this->segments[$last] = ucfirst($this->segments[$last]);
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
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.$this->class.ucfirst($this->method);
    if (class_exists($view_class)) {
      return $view_class;
    }
    $view_class = VIEW_NAMESPACE.NAMESPACE_SEPARATOR.ucfirst($this->method);
    if (class_exists($view_class)) {
      return $view_class;
    } else {
      return null;
    }
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
    return str_replace(NAMESPACE_SEPARATOR, SCHEMA_SEPARATOR ,strtolower($this->class));
  }

  public function get_page_class() {
    return DEFAULT_PAGE_CLASS;
  }

  public static function base_url() {
    $current_url = trim(self::current_url(), '/');
    $base_url = str_replace($current_url, '', $_SERVER['REQUEST_URI']);
    return $base_url;
  }

  public static function current_url() {
    
    if (isset($_SERVER['PATH_INFO'])) {
      return $_SERVER['PATH_INFO'];
    } else {
      return '/'.strtolower(DEFAULT_CONTROLLER).'/'.DEFAULT_METHOD;
    }
  }
}
