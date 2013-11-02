<?php
namespace types;

use core\Field;

class TimestampWithTimeZone extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array('type' => 'datetime');
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function kind() {
    return self::KIND_DATETIMETZ;
  }
}
