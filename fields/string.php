<?php
namespace fields;

use core\BaseField;
use core\Messages;

class String extends BaseField {

  public function control($name, $default = '', $attrs = array()) {
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    parent::control($name, $default, array_merge($attributes, $attrs));
  }

  public function validate(&$value, $prefix = '') {
    if (!parent::validate($value, $prefix)) return false;

    if (!is_null($this->character_maximum_length) && mb_strlen($value) > $this->character_maximum_length) {
      Messages::error_item(sprintf(_('%s can\'t be longer than %d characters.'), $this->label($prefix), $this->character_maximum_length));
      return false;
    }

    return true;
  }

  public function kind() {
    return self::KIND_STRING;
  }
}
