<?php

class types_String extends core_BaseType implements core_Type {

  public function input($default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'text'
    );
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    if ($default) {
      $attributes['value'] = $default;
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function output($value) {
    echo $this->escape($value);
  }

  public function validate($value) {
    if (is_null($this->character_maximum_length)) {
      return true;
    } else {
      return strlen($value) <= $this->character_maximum_length;
    }
  }
}
