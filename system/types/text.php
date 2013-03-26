<?php
namespace types;

use types\String;

class Text extends String {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'name' => $name,
      'type' => null,
      'value' => null,
    );
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function _input() {
    // get all the attributes assigned by parent, but use textarea instead
    call_user_func_array(array($this, '_textarea'), func_get_args());
  }

  public function kind() {
    return self::KIND_TEXT;
  }
}
