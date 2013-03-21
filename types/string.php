<?php

class types_String extends core_BaseType {

  public function widget($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'text',
      'name' => $name,
    );
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    if ($this->is_nullable == 'NO') {
      $attributes['required'] = 'required';
    }
    if ($this->is_updatable == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    if ($default !== '') {
      $attributes['value'] = $default;
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function format($value) {
    echo $this->escape($value);
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    if (is_null($this->character_maximum_length)) {
      return true;
    } else {
      return strlen($value) <= $this->character_maximum_length;
    }
  }

  public function kind() {
    return self::KIND_STRING;
  }
}
