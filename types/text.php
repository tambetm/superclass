<?php
namespace types;

use types\String;

class Text extends String {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'name' => $name,
    );
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    if ($this->is_nullable == 'NO') {
      $attributes['required'] = 'required';
    }
    if ($this->is_updatable == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    $this->_textarea(array_merge($attributes, $attrs), null, $default);
  }

  public function kind() {
    return self::KIND_TEXT;
  }
}
