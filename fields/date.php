<?php
namespace fields;

use core\BaseField;

class Date extends BaseField {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'date');
    parent::control($name, $default, array_merge($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_DATE;
  }
}
