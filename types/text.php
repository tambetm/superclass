<?php

class types_Text extends types_String {

  public function widget($name, $default = '', $attrs = array()) {
    $attributes = array(
      'name' => $name,
    );
    if (!is_null($this->character_maximum_length)) {
      $attributes['maxlength'] = $this->character_maximum_length;
    }
    if ($this->is_nullable == 'NO') {
      $attributes['required'] = 'required';
    }
    $this->_textarea(array_merge($attributes, $attrs), null, $default);
  }

  public function kind() {
    return self::KIND_TEXT;
  }
}
