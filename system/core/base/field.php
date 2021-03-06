<?php
namespace core\base;

use helpers\String;
use helpers\Messages;

abstract class Field extends \core\HTML implements \core\interfaces\Field {
  protected $column;
  protected $model;
  
  public function __construct($column, $model) {
    $this->column = $column;
    $this->model = $model;
  }

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'text',
      'name' => $name,
    );
    /*
    if ($this->column['is_nullable'] == 'NO') {
      $attributes['required'] = 'required';
    }
    if ($this->column['is_updatable'] == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    */
    if ($default !== '') {
      $attributes['value'] = $default;
    }
    $this->_input(self::merge_attributes($attributes, $attrs));
  }

  public function output($value) {
    echo self::escape($this->format($value));
  }

  public function format($value) {
    return $value;
  }

  public function validate(&$value) {
    if ((is_null($value) || $value === '') && $this->column['is_nullable'] == 'NO') {
      Messages::error_item(sprintf(_('%s cannot be empty.'), $this->label()));
      return false;
    } 
    
    return true;
  }

  public function label() {
    return isset($this->column['label']) ? $this->column['label'] : String::human($this->column['column_name']);
  }

  public function database_type() {
    return $this->column['udt_name'];
  }

  public function default_value() {
    return $this->column['column_default'];
  }

  public function is_updatable() {
    return $this->column['is_updatable'] == 'YES';
  }
}
