<?php
namespace types;

use core\Field;

class Enum extends Field {

  protected $options;
  protected $default;
  // loop variables
  protected $value;
  protected $label;
  
  public function __construct($column, $model) {
    parent::__construct($column, $model);
    if (!isset($this->column['options']) || !is_array($this->column['options'])) {
      $column_name = $this->column['column_name'];
      throw new \InvalidArgumentException("Column data doesn't include option values for '$column_name'");
    }
    $this->options = $this->column['options'];
  }

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'name' => $name,
      'value' => null
    );
    $this->default = $default;
    parent::control($name, null, self::merge_attributes($attributes, $attrs));
  }

  protected function _input() {
     // get all the attributes assigned by parent, but use select instead
    call_user_func_array(array($this, '_select'), func_get_args());
  }

  protected function select() {
    $options = $this->options;
    if ($this->column['is_nullable'] == 'YES') {
      $options = array('' => '') + $options;
    }
    foreach ($options as $this->value => $this->label) {
      $attributes = array('value' => $this->value);
      if ($this->value == $this->default) {
        $attributes['selected'] = 'selected';
      }
      $this->_option($attributes);
    }
  }

  protected function option() {
    echo self::escape($this->label);
  }

  public function format($value) {
    return isset($this->options[$value]) ? $this->options[$value] : $value;
  }

  public function kind() {
    return self::KIND_ENUM;
  }
}
