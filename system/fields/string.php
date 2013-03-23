<?php
namespace fields;

use core\Field;

class String extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array();
    if (!is_null($this->column['character_maximum_length'])) {
      $attributes['maxlength'] = $this->column['character_maximum_length'];
    }
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function validate(&$value, &$error) {
    if (!parent::validate($value, $error)) return false;

    if (!is_null($this->column['character_maximum_length']) && mb_strlen($value) > $this->column['character_maximum_length']) {
      $error = sprintf(_('%s cannot be longer than %d characters.'), $this->label(), $this->column['character_maximum_length']);
      return false;
    }

    return true;
  }

  public function kind() {
    return self::KIND_STRING;
  }
}
