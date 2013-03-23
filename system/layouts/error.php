<?php
namespace layouts;

use layouts\HTML5Layout;
use views\Messages;

class Error extends HTML5Layout {

  protected $exception;

  public function __construct($exception) {
    parent::__construct(null);
    $this->exception = $exception;
  }

  public function title() {
    echo 'An Error Occurred...';
  }

  public function body() {
    $this->messages();
    $this->_title('h1');
    $this->_message('p', array('id' => 'message'));
    $this->_stack('p', array('id' => 'stack'));
    parent::body();
  }

  public function messages() {
    /*
    $messages = new Messages();
    $messages->render();
    */
  }

  public function message() {
    echo nl2br($this->exception->getMessage()." in ".$this->exception->getFile()." on line ".$this->exception->getLine());
  }

  public function stack() {
    echo nl2br($this->exception->getTraceAsString());
  }
}
