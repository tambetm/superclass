<?php
namespace types;

use core\BaseType;
use core\Messages;

class String extends BaseType {

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'text',
      'name' => $name,
    );
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    /*
    if ($this->is_nullable == 'NO') {
      $attributes['required'] = 'required';
    }
    if ($this->is_updatable == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    */
    if ($default !== '') {
      $attributes['value'] = $default;
    }
    $this->_input(array_merge($attributes, $attrs));
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
