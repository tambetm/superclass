<?php

class layouts_Error extends layouts_HTML5 {

  protected $exception;

  public function __construct($exception) {
    $this->exception = $exception;
  }

  public function title() {
    echo 'An Error Occurred...';
  }

  public function body() {
    $this->_title('h1');
    $this->_message('p', array('id' => 'message'));
    $this->_stack('p', array('id' => 'stack'));
    parent::body();
  }

  public function message() {
    echo nl2br($this->exception->getMessage());
  }

  public function stack() {
    echo nl2br($this->exception->getTraceAsString());
  }
}
