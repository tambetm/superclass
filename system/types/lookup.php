<?php
namespace types;

use types\Enum;
use core\Model;
use helpers\Arrays;

class Lookup extends Enum {

  public function __construct($column, $model) {
    if (!isset($column['sql'])) {
      throw new \InvalidArgumentException("Missing lookup config for column '$column[column_name]'");
    }
    $data = $model->db()->select_all($column['sql']);
    foreach($data as $row) {
      $value = array_shift($row);
      $label = array_shift($row);
      $options[$value] = $label;
    }
    $column['options'] = $options;
    parent::__construct($column, $model);
  }
}
