<?php
namespace types;

use core\Field;
use helpers\Locale;
use helpers\Messages;

class DoublePrecision extends Field {

  protected $locale;

  public function __construct($column, $model) {
    parent::__construct($column, $model);
    $this->locale = Locale::get_conventions();
  }

  public function control($name, $default = '', $attrs = array()) {
    $attributes = array(
      'type' => 'number',
    );
    parent::control($name, $default, self::merge_attributes($attributes, $attrs));
  }

  public function format($value) {
    if (!is_null($value) && $value !== '') {
      // %f in sprintf() is locale-aware
      return self::escape(sprintf('%f', $value));
    }
  }

  public function validate(&$value) {
    if (!parent::validate($value)) return false;

    // empty field is valid
    if (is_null($value) || $value === '') return true;

    // support only decimal point as separator
    $pattern = '/^[+-]?\d+['.$this->locale['decimal_point'].']?\d*$/';
    if (!preg_match($pattern, $value)) {
      Messages::error_item(sprintf(_('%s must be a number.'), $this->label()));
      return false;
    }

    return true;
  }

  public function kind() {
    return self::KIND_NUMBER;
  }

}
