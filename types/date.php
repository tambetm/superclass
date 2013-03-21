<?php

class types_date extends core_BaseType {

  public function widget($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'date',
      'name' => $name,
    );
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

    return true;  // TODO
  }

  public function kind() {
    return self::KIND_DATE;
  }
}
