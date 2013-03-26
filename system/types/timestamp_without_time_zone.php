<?php
namespace types;

use core\Field;

class TimestampWithoutTimeZone extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'datetime-local');
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_DATETIME;
  }
}
