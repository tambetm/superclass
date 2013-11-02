<?php
namespace types;

use types\Numeric;

class Money extends Numeric {
  
  public function format($value) {
    if (!is_null($value) && $value !== '') {
      return self::escape(number_format($value, $this->column['numeric_scale'], $this->locale['mon_decimal_point'], $this->locale['thousands_sep']));
    }
  }

}
