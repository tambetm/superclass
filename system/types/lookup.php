<?php
namespace types;

use types\Enum;
use core\Model;
use helpers\Arrays;

class Lookup extends Enum {

  public function __construct($column) {
    $model = new Model($column['lookup_table']);
    $data = $model->select(Arrays::get($column, 'lookup_where'), Arrays::get($column, 'lookup_order_by'));
    foreach($data as $row) {
      $options[$row[$column['lookup_value']]] = $row[$column['lookup_label']];
    }
    $column['options'] = $options;
    parent::__construct($column);
  }
}
