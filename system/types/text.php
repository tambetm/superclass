<?php
namespace types;

use types\String;

class Text extends String {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'name' => $name,
    );
    $this->_textarea(self::merge_attributes($attributes, $attrs), $default);
  }

  public function kind() {
    return self::KIND_TEXT;
  }
}
