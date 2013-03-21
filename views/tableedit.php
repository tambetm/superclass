<?php

class views_Tableedit extends views_Table {

  protected $primary_key;

  public function __construct($model) {
    parent::__construct($model);
    $this->primary_key = $model->primary_key();
  }

  public function render() {
    $this->_form(array('action' => core_URL::relative_url('updatemany'), 'method' => 'post'));
  }

  protected function form() {
    parent::render();
    $this->_button(array('type' => 'submit', 'class' => 'btn btn-primary'), null, 'Save');
  }

  protected function table_tbody_tr_td() {
    if (isset($this->primary_key[$this->field])) {
      $this->_input(array(
        'type' => 'hidden', 
        'name' => $this->model->table().'['.$this->nr.'][where]['.$this->field.']', 
        'value' => $this->row[$this->field],
      ));
    }
    $this->model->field($this->field)->widget($this->model->table().'['.$this->nr.'][data]['.$this->field.']', $this->row[$this->field]);
  }
}
