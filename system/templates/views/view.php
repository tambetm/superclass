<?php
namespace templates\views;

use core\View as _View;
use core\Field;
use helpers\URL;
use helpers\Config;

class View extends _View {

  protected $fields;
  protected $config;

  // internal loop variables
  protected $nr;
  protected $row;
  protected $field;
  protected $field_meta;

  public function __construct($model) {
    parent::__construct($model);
    $this->fields = $model->fields();

    Config::load($this->config, 'config/views/view.php');
    Config::load($this->config, VIEW_NAMESPACE.DIRECTORY_SEPARATOR."_{$this->model_name}_view.php");
  }

  public function title() {
    return sprintf(_('%s view'), $this->model->caption());
  }

  public function render() {
    $this->_form(array('action' => URL::self(), 'method' => 'post', 'class' => 'form-horizontal'));
  }

  protected function form() {
    if (is_array($this->data)) {
      foreach ($this->data as $this->nr => $this->row) {
        $this->_fieldset();
      }
    }
    $this->_form_actions('div', array('class' => 'form-actions'));
  }

  protected function fieldset() {
    if (isset($this->config['caption_field'])) {
      $this->_legend(null, $this->row[$this->config['caption_field']]);
    }
    if (is_array($this->fields)) {
      foreach ($this->fields as $this->field => $this->field_meta) {
        $this->_control_group('div', array('class' => 'control-group'));
      }
    }
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
    $this->_edit_a(array('href' => URL::self('edit'), 'class' => 'btn'), _('Edit'));
    $this->_back_a(array('href' => URL::self('table', array()), 'class' => 'btn'), _('Back'));
  }
}
