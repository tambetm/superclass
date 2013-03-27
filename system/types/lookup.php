<?php
namespace types;

use types\Enum;
use core\Model;
use helpers\Arrays;

class Lookup extends Enum {

  public function __construct($column) {
    if (!isset($column['lookup'])) {
      throw new \InvalidArgumentException("Missing lookup config for column '$column[column_name]'");
    }
    $model = new Model($column['lookup']['table']);
    $data = $model->select(Arrays::get($column['lookup'], 'where'), Arrays::get($column['lookup'], 'order_by'));
    foreach($data as $row) {
      $options[$row[$column['lookup']['value']]] = $row[$column['lookup']['label']];
    }
    $column['options'] = $options;
    parent::__construct($column);
  }
}
