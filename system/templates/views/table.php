<?php
namespace templates\views;

use core\View;
use helpers\URL;
use helpers\Arrays;
use helpers\Config;

class Table extends View {

  protected $config;
  protected $fields;
  protected $primary_key;

  // internal loop variables
  protected $name;
  protected $field_meta;
  protected $nr;
  protected $row;
  protected $keys;

  public function __construct($model) {
    parent::__construct($model);
    $this->fields = $model->fields();
    $this->primary_key = $model->primary_key();

    Config::load($this->config, 'config/views/table.php');
    Config::load($this->config, VIEW_NAMESPACE.DIRECTORY_SEPARATOR."_{$this->model_name}_table.php");

    if (isset($this->config['columns']) && is_array($this->config['columns'])) {
      $this->fields = array_intersect_key($this->fields, $this->config['columns']);
    }
  }

  public function title() {
    return $this->model->caption();
  }

  public function render() {
    $attributes = array('id' => $this->model_name, 'class' => 'table table-bordered table-hover');
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
    $this->table_thead_tr_actions();
  }

  protected function table_thead_tr_th() {
    if (isset($this->config['columns'][$this->field]['label'])) {
      echo $this->config['columns'][$this->field]['label'];
    } else {
      echo $this->field_meta->label();
    }
  }

  protected function table_thead_tr_actions() {
    $this->_table_thead_tr_view_th();
    $this->_table_thead_tr_edit_th();
    $this->_table_thead_tr_delete_th();
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
    $this->keys = array_intersect_key($this->row, $this->primary_key);
    $this->table_tbody_tr_actions();
  }

  protected function table_tbody_tr_actions() {
    $this->_table_tbody_tr_view_td();
    $this->_table_tbody_tr_edit_td();
    $this->_table_tbody_tr_delete_td();
  }

  protected function table_tbody_tr_td() {
    $this->field_meta->output($this->row[$this->field]);
  }

  protected function table_tbody_tr_view_td() {
    $this->_view_a(array('href' => URL::self('view', $this->keys), 'class' => 'btn btn-mini view'), _('View'));
  }

  protected function table_tbody_tr_edit_td() {
    $this->_edit_a(array('href' => URL::self('edit', $this->keys), 'class' => 'btn btn-mini edit'), _('Edit'));
  }

  protected function table_tbody_tr_delete_td() {
    $this->_delete_a(array('href' => URL::self('delete', $this->keys), 'class' => 'btn btn-mini btn-danger delete'), _('Delete'));
  }

  protected function table_tfoot() {
    // default is empty
  }

  protected function table_actions() {
    $this->_add_new_a(array('href' => URL::self('add_new'), 'class' => 'btn add_new'), _('Add new'));
    $this->_table_edit_a(array('href' => URL::self('table_edit'), 'class' => 'btn table_edit'), _('Edit table'));
  }
}
