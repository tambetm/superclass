<?php
namespace templates\views;

use helpers\URL;
use helpers\Request;
use helpers\Messages;

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

  protected function _control_group($tag, $attributes) {
    $field_status = Messages::field_status($this->nr, $this->field);
    if ($field_status) {
      $attributes = self::merge_attributes($attributes, array('class' => $field_status));
    }
    parent::_control_group($tag, $attributes);
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
    $field_message = Messages::field_message($this->nr, $this->field);
    if ($field_message) {
      $this->_field_span(array('class' => 'help-inline message'), $field_message);
    }
  }

  protected function form_actions() {
    $this->_save_button(array('type' => 'submit', 'class' => 'btn btn-primary'), _('Save changes'));
    $this->_back_a(array('href' => URL::self('table', array()), 'class' => 'btn'), _('Back'));
  }
}
