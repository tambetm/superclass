<?php
namespace templates\views;

use helpers\URL;

class Edit extends View {

  public function title() {
    return sprintf(_('%s edit'), $this->model->caption());
  }

  protected function controls() {
    $this->field_meta->control("{$this->model_name}_{$this->nr}_{$this->field}", $this->row[$this->field]);
  }

  protected function form_actions() {
    $this->_button(array('type' => 'submit', 'class' => 'btn btn-primary'), _('Save changes'));
    $this->_a(array('href' => URL::self('table', array()), 'class' => 'btn'), _('Back'));
  }
}
