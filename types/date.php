<?php
namespace types;

use core\BaseType;

class Date extends BaseType {

  public function control($name, $default = '', $attrs = array()) {
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

  public function kind() {
    return self::KIND_DATE;
  }
}
