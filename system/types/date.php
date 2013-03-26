<?php
namespace types;

use core\Field;

class Date extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'date');
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_DATE;
  }
}
