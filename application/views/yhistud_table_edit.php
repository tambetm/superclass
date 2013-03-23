<?php
namespace views;

use views\TableEdit;

class YhistudTableEdit extends TableEdit {

  public function form() {
    parent::form();
    $this->_a(array('href' => 'yhistud/table', 'class' => 'btn'), null, _('Back'));
  }
}
