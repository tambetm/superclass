<?php
namespace templates\layouts;

use templates\layouts\Bootstrap;
use templates\blocks\Messages;

class Error extends Bootstrap {

  protected $exception;

  public function __construct($exception) {
    parent::__construct(null);
    $this->exception = $exception;
  }

  public function title() {
    echo _('We are very sorry...');
  }

  public function body_content() {
    $this->messages();
    $this->_title('h1');
    $this->_p();
  }

  public function messages() {
    // try to avoid the case when requesting messages itself causes an error
    if (session_id() || !headers_sent()) {
      $messages = new Messages();
      $messages->render();
    }
  }

  public function p() {
    echo _('Something caused an error. Tell us what you did, by filling out the form below:');
  }
}
