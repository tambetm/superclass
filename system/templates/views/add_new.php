<?php
namespace templates\views;

class AddNew extends Edit {

  public function __construct($model) {
    parent::__construct($model);
    $this->redirect = 'table';
  }

  public function title() {
    return sprintf(_('%s new'), $this->model->caption());
  }

  public function get() {
    $this->data = array($_GET + $this->model->defaults());
    $this->operations = array('insert');
  }
}
