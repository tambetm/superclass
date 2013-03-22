<?php
namespace types;

use core\BaseType;
use core\Messages;

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

  public function validate(&$value, $prefix = '') {
    if (!parent::validate($value, $prefix)) return false;

    if (!($value == 't' || $value == 'f')) {
      Messages::error_item(sprintf(_('%s can only have value t or f.'), $this->label($prefix)));
      return false;
    }

    return true;
  }

  public function kind() {
    return self::KIND_BOOLEAN;
  }
}
