<?php
namespace types;

use core\Field;
use helpers\Locale;
use helpers\Messages;

class Integer extends Field {
  
  protected $locale;
  protected $max;
  protected $min;

  public function __construct($column, $model) {
    parent::__construct($column, $model);
    $this->locale = Locale::get_conventions();
    $this->max = pow($this->column['numeric_precision_radix'], $this->column['numeric_precision'] - 1) - 1;
    $this->min = -$this->max;
  }

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'number',
      'max' => $this->max,
      'min' => $this->min,
    );
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function format($value) {
    if (!is_null($value) && $value !== '') {
      return self::escape(sprintf('%d', $value));
    }
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    // empty field is valid
    if (is_null($value) || $value === '') return true;

    // support both decimal point and monetary decimal point as separator
    $pattern = '/^[+-]?\d+$/';
    if (!preg_match($pattern, $value)) {
      Messages::error_item(sprintf(_('%s must be a number.'), $this->label()));
      return false;
    }

    /*
    if ($value > $this->max || $value < $this->min) {
      Messages::error_item(sprintf(_('%s must be between %d and %d.'), $this->label(), $this->min, $this->max));
      return false;
    }
    */

    return true;
  }

  public function kind() {
    return self::KIND_NUMBER;
  }
}
