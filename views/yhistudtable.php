<?php

class views_YhistudTable extends views_Table {

  public function render() {
    parent::render();
    $this->_a(array('href' => core_URL::relative_url('tableedit'), 'class' => 'btn'), null, _('Edit'));
  }
}
