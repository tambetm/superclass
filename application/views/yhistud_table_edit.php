<?php
namespace views;

use templates\views\TableEdit;

class YhistudTableEdit extends TableEdit {

  public function form() {
    parent::form();
    $this->_a(array('href' => 'table', 'class' => 'btn'), _('Back'));
  }
}
