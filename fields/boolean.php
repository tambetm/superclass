<?php
namespace fields;

use core\BaseField;
use core\Messages;

class Boolean extends BaseField {

  public function control($name, $default = 'f', $attrs = array()) {
    $this->_input(array(
      'type' => 'hidden',
      'name' => $name,
      'value' => 'f',
    ));
    $attributes = array(
      'type' => 'checkbox',
      'required' => null,
    );
    if ($default == 't') {
      $attributes['checked'] = 'checked';
    }
    parent::control($name, 't', array_merge($attributes, $attrs));
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
