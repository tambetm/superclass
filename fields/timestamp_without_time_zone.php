<?php
namespace fields;

use core\BaseField;

class TimestampWithoutTimeZone extends BaseField {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'datetime-local');
    parent::control($name, $default, array_merge($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_DATETIME;
  }
}
