<?php

class types_Numeric extends core_BaseType {

  protected $locale;
  protected $max;
  protected $min;

  public function __construct($meta) {
    parent::__construct($meta);
    $this->locale = core_Locale::instance();
    $this->max = pow($this->numeric_precision_radix, $this->numeric_precision - $this->numeric_scale) - pow($this->numeric_precision_radix, -$this->numeric_scale);
    $this->min = -$this->max;
  }

  public function widget($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'number',
      'name' => $name,
      'max' => $this->max,
      'min' => $this->min,
    );
    if ($this->is_nullable == 'NO') {
      $attributes['required'] = 'required';
    }
    if ($this->is_updatable == 'NO') {
      $attributes['readonly'] = 'readonly';
    }
    if ($default !== '') {
      $attributes['value'] = $default;
    }
    $this->_input(array_merge($attributes, $attrs));
  }

  public function format($value) {
    if (!is_null($value) && $value !== '') {
      echo $this->escape(number_format($value, $this->numeric_scale, $this->locale->decimal_point, $this->locale->thousands_sep));
    }
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    // support both decimal point and monetary decimal point as separator
    $pattern = '/^[+-]?\d+['.$this->locale->decimal_point.$this->locale->mon_decimal_point.']?\d*$/';
    if (!preg_match($pattern, $value)) return false;
    if ($value > $this->max) return false;
    if ($value < $this->min) return false;
    return true;
  }

  public function kind() {
    return self::KIND_NUMBER;
  }
}
