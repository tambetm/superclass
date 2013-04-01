<?php
namespace templates\views;

use core\View as _View;
use core\Field;
use helpers\URL;

class View extends _View {

  protected $model_name;
  protected $fields;
  protected $data;

  // internal loop variables
  protected $nr;
  protected $row;
  protected $field;
  protected $field_meta;

  public function __construct($model) {
    parent::__construct($model);
    $this->model_name = $model->name();
    $this->fields = $model->fields();
  }

  public function get() {
    $this->data = $this->model->select($_GET);
  }

  public function title() {
    return sprintf(_('%s view'), $this->model->caption());
  }

  public function render() {
    if (is_array($this->data)) {
      foreach ($this->data as $this->nr => $this->row) {
        $this->_form(array('action' => URL::self(), 'method' => 'POST', 'class' => 'form-horizontal'));
      }
    }
  }

  protected function form() {
    if (is_array($this->fields)) {
      foreach ($this->fields as $this->field => $this->field_meta) {
        $this->_control_group('div', array('class' => 'control-group'));
      }
    }
    $this->_form_actions('div', array('class' => 'form-actions'));
  }

  protected function control_group() {
    $this->_control_label(array('class' => 'control-label'));
    $this->_controls('div', array('class' => 'controls'));
  }

  protected function control_label() {
    echo self::escape($this->field_meta->label());
  }

  protected function controls() {
    $this->_control('span', array('class' => 'uneditable-input'));
  }

  protected function control() {
    echo self::escape($this->field_meta->format($this->row[$this->field]));
  }

  protected function form_actions() {
    $this->_a(array('href' => URL::self('edit'), 'class' => 'btn'), _('Edit'));
    $this->_a(array('href' => URL::self('table', array()), 'class' => 'btn'), _('Back'));
  }
}
