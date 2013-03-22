<?php
namespace views;

use core\BaseView;

class Table extends BaseView {

  protected $meta;
  protected $data;
  protected $config;

  // internal loop variables
  protected $field;
  protected $field_meta;
  protected $nr;
  protected $row;

  public function __construct($model) {
    parent::__construct($model);
    include('config/table.php');
    $this->config = $config;
    $this->meta = $model->meta();
  }

  public function get() {
    $this->data = $this->model->select($_GET);
  }

  public function render() {
    $attributes = array();
    if (isset($this->config['class'])) {
      $attributes['class'] = $this->config['class'];
    }
    $this->_table($attributes);
  }

  protected function table() {
    $this->_table_thead();
    $this->_table_tbody();
    // output footer only if it has contents
    $this->_table_tfoot(null, null, true);
  }

  protected function table_thead() {
    $this->_table_thead_tr();
  }

  protected function table_thead_tr() {
    foreach ($this->meta as $this->field => $this->field_meta) {
      $this->_table_thead_tr_th(array('class' => $this->field.' '.$this->field_meta['udt_name']));
    }
  }

  protected function table_thead_tr_th() {
    echo $this->model->field($this->field)->label();
  }

  protected function _table_tbody() {
    // show body only if we have data. don't use output buffering!
    if (is_array($this->data) && count($this->data) > 0) {
      parent::_table_tbody();
    }
  }

  protected function table_tbody() {
    foreach ($this->data as $this->nr => $this->row) {
      $this->_table_tbody_tr();
    }
  }

  protected function table_tbody_tr() {
    foreach ($this->meta as $this->field => $this->field_meta) {
      $this->_table_tbody_tr_td(array('class' => $this->field.' '.$this->model->field($this->field)->kind()));
    }
  }

  protected function table_tbody_tr_td() {
    $this->model->field($this->field)->output($this->row[$this->field]);
  }

  protected function table_tfoot() {
    // default is empty
  }
}
