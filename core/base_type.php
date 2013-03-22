<?php
namespace core;

use core\HTML;
use core\String;
use interfaces\Type;

abstract class BaseType extends HTML implements Type {
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

  public function output($value) {
    echo self::escape($this->format($value));
  }

  public function format($value) {
    return $value;
  }

  public function validate(&$value, $prefix = '') {
    if ((is_null($value) || $value === '') && $this->is_nullable == 'NO') {
      Messages::error_item(sprintf(_('%s cannot be empty.'), $this->label($prefix)));
      return false;
    } 
    
    return true;
  }

  public function label($prefix = '') {
    return $prefix.String::humanize($this->column_name);
  }
}
