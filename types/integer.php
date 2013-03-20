<?php

class types_Integer extends core_BaseType implements core_Type {
  
  public function input($default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'number'
    );
    if ($default) {
      $attributes['value'] = $default;
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function output($value) {
    echo $this->escape($value);
  }

  public function validate($value) {
    return $value === '' || ctype_digit($value);
  }
}
