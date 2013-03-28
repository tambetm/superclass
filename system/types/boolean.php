<?php
namespace types;

use core\Field;
use helpers\Messages;

class Boolean extends Field {

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
    parent::control($name, 't', self::merge_attributes($attributes, $attrs));
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    if (!($value == 't' || $value == 'f')) {
      Messages::error_item(sprintf(_('%s can only have value t or f.'), $this->label()));
      return false;
    }

    return true;
  }

  public function output($value) {
    if ($value == 't') {
      $this->_i(array('class' => 'icon-ok'));
    }
  }

  public function kind() {
    return self::KIND_BOOLEAN;
  }
}
