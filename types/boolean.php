<?php

class types_Boolean extends core_BaseType {

  public function widget($name, $checked = false, $attrs = array()) {
    $this->_input(array(
      'type' => 'hidden',
      'name' => $name,
      'value' => 'f',
    ));
    $attributes = array(
      'type' => 'checkbox',
      'name' => $name,
      'value' => 't',
    );
    if ($checked) {
      $attributes['checked'] = 'checked';
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function format($value) {
    echo $this->escape($value);
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    return $value == 't' || $value == 'f';
  }

  public function kind() {
    return self::KIND_BOOLEAN;
  }
}
