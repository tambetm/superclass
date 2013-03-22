<?php
namespace types;

use core\BaseType;

class Boolean extends BaseType {

  public function control($name, $checked = false, $attrs = array()) {
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
    if ($this->is_updatable == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    if ($checked) {
      $attributes['checked'] = 'checked';
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    return $value == 't' || $value == 'f';
  }

  public function kind() {
    return self::KIND_BOOLEAN;
  }
}
