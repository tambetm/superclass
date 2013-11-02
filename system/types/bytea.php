<?php
namespace types;

use core\Field;
use helpers\Messages;

class Bytea extends Field {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'file',
    );
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function validate(&$value) {
    return true;
  }

  public function kind() {
    return self::KIND_FILE;
  }
}
