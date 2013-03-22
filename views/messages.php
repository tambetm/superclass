<?php
namespace views;

use core\HTML;
use core\Messages as core_Messages;

class Messages extends HTML {
  static public $headings = array(
      'error' => 'Error',
      'alert' => 'Alert',
      'info' => 'Info',
      'success' => 'Success',
      'log' => 'Log',
      'debug' => 'Debug',
    );
  
  protected $messages;
  // internal loop variables
  protected $message;
  
  public function __construct() {
    $this->messages = core_Messages::messages();
  }

  public function render() {
    if (is_array($this->messages)) {
      $this->_messages('div', array('id' => 'messages'));
      $this->_script(array('type' => 'text/javascript'));
    }
  }

  public function messages() {
    foreach ($this->messages as $this->message) {
      $this->_message('div', array('class' => 'alert alert-block alert-'.$this->message['type'].' fade in'));
    }
  }

  public function message() {
    $this->_button(array('type' => 'button', 'class' => 'close', 'data-dismiss' => 'alert'));
    $this->_heading('h4');
    $this->_text('p');
  }

  public function button() {
    echo '&times;';
  }

  public function heading() {
    echo self::escape(self::$headings[$this->message['type']]);
  }

  public function text() {
    if ($this->message['type'] == 'log') {
      echo $this->message['text'];
    } else {
      echo self::escape($this->message['text']);
    }
  }

  public function script() {
    ?>$('.alert').alert();<?php
  }
}
