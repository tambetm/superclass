<?php
namespace views;

use views\Table;
use core\URL;

class YhistudTable extends Table {

  public function render() {
    parent::render();
    $this->_a(array('href' => 'yhistud/table_edit', 'class' => 'btn'), null, _('Edit'));
  }
}
