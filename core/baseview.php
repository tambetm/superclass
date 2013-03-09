<?php

class core_BaseView {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function render() {
    // TODO: move to interface core_View?
  }

  public function __call($name, $arguments) {
    // all internal methods must start with _
    if (strpos($name, '_') !== 0) {
      throw new BadMethodCallException('Invalid method '.$name);
    }

    // call the same method without leading underscore
    $method = substr($name, 1);

    // if third argument is true, then output tag only if it has contents
    if (isset($arguments[2]) && $arguments[2] === true) {
      ob_start();
      call_user_func(array($this, $method));
      $content = ob_get_clean();
      if (!$content) return;
    }

    // first non-array argument is tag name.
    if (isset($arguments[0]) && !is_null($arguments[0]) && !is_array($arguments[0])) {
      $tag = $arguments[0];
    } else if (isset($arguments[1]) && !is_null($arguments[1]) && !is_array($arguments[1])) {
      $tag = $arguments[1];
    } else {
      // if missing, use the last part of method name.
      $tags = explode('_', $name);
      $tag = end($tags);
    }

    // first array argument is list of attributes. NB! null is not an array!
    if (isset($arguments[0]) && is_array($arguments[0])) {
      $attributes = $arguments[0];
    } else if (isset($arguments[1]) && is_array($arguments[1])) {
      $attributes = $arguments[1];
    }

    // concatenate attributes into string.
    $attrs = '';
    if (isset($attributes)) {
      foreach ($attributes as $name => $value) {
        // escape attribute values for convenience
        $attrs .= ' '.$name.'="'.htmlspecialchars($value).'"';
      }
    }

    // output tag and it's contents
    if (isset($content)) {
      echo "<$tag$attrs>$content</$tag>";
    } else {
      echo "<$tag$attrs>";
      call_user_func(array($this, $method));
      echo "</$tag>";
    }
  }
}
