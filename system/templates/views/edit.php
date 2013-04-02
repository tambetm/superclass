<?php
namespace templates\views;

use helpers\URL;
use helpers\Request;

class Edit extends View {

  public function __construct($model) {
    parent::__construct($model);
    $this->primary_key = $model->primary_key();
    $this->redirect = 'table';
  }

  public function title() {
    return sprintf(_('%s edit'), $this->model->caption());
  }

  protected function fieldset() {
    parent::fieldset();
    if (is_array($this->primary_key)) {
      foreach($this->primary_key as $field => $dummy) {
        $this->_where_input(array(
          'type' => 'hidden', 
          'name' => "{$this->model_name}[where][{$this->nr}][{$field}]", 
          'value' => isset($this->where[$this->nr][$field]) ? 
            $this->where[$this->nr][$field] : 
            $this->row[$field],
        ));
      }
    } else {
      foreach($this->row as $field => $value) {
        $this->_where_input(array(
          'type' => 'hidden', 
          'name' => "{$this->model_name}[where][{$this->nr}][{$field}]", 
          'value' => $value,
        ));
      }
    }
    // select this record
    $this->_selector_input(array(
      'type' => 'hidden',
      'name' => "{$this->model_name}[selector][{$this->nr}]",
      'value' => 't',
    ));
    // update this record
    $this->_operation_input(array(
      'type' => 'hidden',
      'name' => "{$this->model_name}[operation][{$this->nr}]",
      'value' => isset($this->operations[$this->nr]) ? $this->operations[$this->nr] : 'update',
    ));
  }
  
  protected function controls() {
    $name = "{$this->model_name}[data][{$this->nr}][{$this->field}]";
    $value = $this->row[$this->field];
    if ($this->field_meta->is_updatable()) {
      $this->field_meta->control($name, $value);
    } else {
      $this->_readonly_input(array('type' => 'hidden', 'name' => $name, 'value' => $value));
      parent::controls();
    }
  }

  protected function form_actions() {
    $this->_save_button(array('type' => 'submit', 'class' => 'btn btn-primary'), _('Save changes'));
    $this->_back_a(array('href' => URL::self('table', array()), 'class' => 'btn'), _('Back'));
  }
}
