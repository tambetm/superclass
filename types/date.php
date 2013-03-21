<?php

class types_date extends core_BaseType {

  public function widget($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'date',
      'name' => $name,
    );
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

    return true;  // TODO
  }

  public function kind() {
    return self::KIND_DATE;
  }
}
