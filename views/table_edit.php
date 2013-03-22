<?php
namespace views;

use views\Table;
use core\URL;
use core\Messages;

class TableEdit extends Table {

  protected $primary_key;
  protected $where;

  public function __construct($model) {
    parent::__construct($model);
    $this->primary_key = $model->primary_key();
  }

  public function post() {
    $table = $this->model->table();
    if (!isset($_POST[$table]['data']) || !is_array($_POST[$table]['data']) 
      || !isset($_POST[$table]['where']) || !is_array($_POST[$table]['where'])) {
      // no data posted
      echo 'NO DATA'; // TODO
      return;
    }
    $this->data = $_POST[$table]['data'];
    $this->where = $_POST[$table]['where'];
    foreach ($this->data as $nr => $row) {
      $where = $this->where[$nr];
      $this->model->update($row, $where);
    }

    Messages::success(_('Changes saved.'));
    URL::redirect(URL::current_url());
  }

  public function render() {
    $this->_form(array('action' => URL::current_url(), 'method' => 'post'));
  }

  protected function form() {
    parent::render();
    $this->_button(array('type' => 'submit', 'class' => 'btn btn-primary'), null, 'Save');
  }

  protected function table_tbody_tr_td() {
    if (isset($this->primary_key[$this->field])) {
      $this->_input(array(
        'type' => 'hidden', 
        'name' => $this->model->table().'[where]['.$this->nr.']['.$this->field.']', 
        'value' => $this->row[$this->field],
      ));
    }
    $this->model->field($this->field)->control($this->model->table().'[data]['.$this->nr.']['.$this->field.']', $this->row[$this->field]);
  }
}
