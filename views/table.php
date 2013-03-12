<?php

class views_Table extends core_BaseView {

  protected $meta;
  protected $data;

  // internal loop variables
  protected $field;
  protected $field_meta;
  protected $nr;
  protected $row;

  function __construct($model) {
    parent::__construct($model);
    $this->meta = $model->meta();
    $this->data = $model->data();
  }

  function render() {
    $this->_table(array('class' => 'table table-bordered table-hover'));
  }

  function table() {
    $this->_table_thead();
    $this->_table_tbody();
    // output footer only if it has contents
    $this->_table_tfoot(null, null, true);
  }

  function table_thead() {
    $this->_table_thead_tr();
  }

  function table_thead_tr() {
    foreach ($this->meta as $this->field => $this->field_meta) {
      $this->_table_thead_tr_th(array('class' => $this->field.' '.$this->field_meta['type']));
    }
  }

  function table_thead_tr_th() {
    echo ucfirst(str_replace('_', ' ', $this->field)); // TODO: add labels to field_meta
  }

  function _table_tbody() {
    // show body only if we have data. don't use output buffering!
    if (is_array($this->data) && count($this->data) > 0) {
      parent::_table_tbody();
    }
  }

  function table_tbody() {
    foreach ($this->data as $this->nr => $this->row) {
      $this->_table_tbody_tr();
    }
  }

  function table_tbody_tr() {
    foreach ($this->meta as $this->field => $this->field_meta) {
      $this->_table_tbody_tr_td(array('class' => $this->field.' '.$this->field_meta['type']));
    }
  }

  function table_tbody_tr_td() {
    echo $this->row[$this->field];
  }

  function table_tfoot() {
    // default is empty
  }
}
