<?php
namespace templates\views;

use core\View;
use helpers\URL;
use helpers\Arrays;
use helpers\Config;

class Table extends View {

  protected $config;
  protected $db;
  protected $table;
  protected $fields;
  protected $data;

  // internal loop variables
  protected $name;
  protected $field_meta;
  protected $nr;
  protected $row;
  protected $action;

  public function __construct($model) {
    parent::__construct($model);
    Config::load($this->config, 'config/views/table.php');
    Config::load($this->config, VIEW_NAMESPACE.DIRECTORY_SEPARATOR.$model->table().'_table_meta.php');

    $this->db = $model->db();
    $this->table = $model->table();
    $this->fields = $model->fields();
    if (isset($this->config['columns']) && is_array($this->config['columns'])) {
      $this->fields = array_intersect_key($this->fields, $this->config['columns']);
    }
  }

  public function get($params) {
    $this->data = $this->model->select($params);
  }

  public function title() {
    return $this->model->caption();
  }

  public function render() {
    $attributes = array('id' => $this->model->table(), 'class' => 'table table-bordered table-hover');
    $this->_table(self::merge_attributes($attributes, Arrays::get($this->config, 'attributes')));
    $this->_table_actions('div', array('class' => 'table-actions'), false);
  }

  protected function table() {
    $this->_table_colgroup();
    $this->_table_thead();
    $this->_table_tbody();
    // output footer only if it has contents
    $this->_table_tfoot();
  }

  protected function table_colgroup() {
    foreach ($this->fields as $this->field => $this->field_meta) {
      $this->_table_colgroup_col(array('class' => $this->field.' '.$this->field_meta->kind()));
    }
  }

  protected function table_thead() {
    $this->_table_thead_tr();
  }

  protected function table_thead_tr() {
    foreach ($this->fields as $this->field => $this->field_meta) {
      $this->_table_thead_tr_th();
    }
  }

  protected function table_thead_tr_th() {
    if (isset($this->config['columns'][$this->field]['label'])) {
      echo $this->config['columns'][$this->field]['label'];
    } else {
      echo $this->field_meta->label();
    }
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
    foreach ($this->fields as $this->field => $this->field_meta) {
      $this->_table_tbody_tr_td();
    }
  }

  protected function table_tbody_tr_td() {
    $this->field_meta->output($this->row[$this->field]);
  }

  protected function table_tfoot() {
    // default is empty
  }

  protected function table_actions() {
    $this->_a(array('href' => URL::self('table_edit'), 'class' => 'btn'), _('Edit table'));
  }

  protected function table_actions_action() {
    call_user_func_array(array($this, '_'), $this->action);
  }
}
