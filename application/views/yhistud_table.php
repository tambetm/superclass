<?php
namespace views;

use templates\views\Table;

class YhistudTable extends Table {

  public function render() {
    parent::render();
    $this->_a(array('href' => 'table_edit', 'class' => 'btn'), _('Edit'));
  }
}
