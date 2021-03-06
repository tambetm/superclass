<?php
namespace types;

use core\Field;
use helpers\Locale;
use helpers\Messages;

class Numeric extends Field {
  
  protected $locale;
  protected $max;
  protected $min;

  public function __construct($column, $model) {
    parent::__construct($column, $model);
    $this->locale = Locale::get_conventions();
    $this->max = pow($this->column['numeric_precision_radix'], $this->column['numeric_precision'] - $this->column['numeric_scale']) - pow($this->column['numeric_precision_radix'], -$this->column['numeric_scale']);
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
      return self::escape(number_format($value, $this->column['numeric_scale'], $this->locale['decimal_point'], $this->locale['thousands_sep']));
    }
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    // empty field is valid
    if (is_null($value) || $value === '') return true;

    // support both decimal point and monetary decimal point as separator
    $pattern = '/^[+-]?\d+['.$this->locale['decimal_point'].$this->locale['mon_decimal_point'].']?\d*$/';
    if (!preg_match($pattern, $value)) {
      Messages::error_item(sprintf(_('%s must be a number.'), $this->label()));
      return false;
    }

    if ($value > $this->max) {
      Messages::error_item(sprintf(_('%s cannot be bigger than %s.'), $this->label(), $this->max));
      return false;
    }

    if ($value < $this->min) {
      Messages::error_item(sprintf(_('%s cannot be smaller than %s.'), $this->label(), $this->min));
      return false;
    }

    return true;
  }

  public function kind() {
    return self::KIND_NUMBER;
  }
}
