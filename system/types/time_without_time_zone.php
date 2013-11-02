<?php
namespace types;

use core\Field;

class TimeWithoutTimeZone extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'time');
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_TIME;
  }
}
