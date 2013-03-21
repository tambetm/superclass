<?php

abstract class core_BaseType extends core_HTMLTemplate implements interfaces_Type {
  protected $meta;
  
  public function __construct($meta) {
    $this->meta = $meta;
  }

  public function __set($name, $value) {
    $this->meta[$name] = $value;
  }

  public function __get($name) {
    return $this->meta[$name];
  }

  public function __isset($name) {
    return isset($this->meta[$name]);
  }

  public function __unset($name) {
    unset($this->meta[$name]);
  }

  public function validate(&$value) {
    if (is_null($value) || $value === '') {
      return $this->is_nullable == 'YES';
    } else {
      return true;
    }
  }

  public function label() {
    return ucfirst(str_replace('_', ' ', $this->column_name));
  }
}
