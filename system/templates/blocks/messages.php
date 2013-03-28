<?php
namespace templates\blocks;

use core\HTML;
use helpers\Messages as _Messages;

class Messages extends HTML {
  
  protected $messages;
  // internal loop variables
  protected $message;
  protected $item;
  
  public function __construct() {
    $this->messages = _Messages::messages();
  }

  public function render() {
    if (is_array($this->messages)) {
      $this->_messages('div', array('id' => 'messages'));
      $this->_script(array('type' => 'text/javascript'));
    }
  }

  public function messages() {
    foreach ($this->messages as $this->message) {
      if ($this->message['type'] == 'log') {
        foreach ($this->message['items'] as $this->item) {
          $this->_pre();
        }
      } else {
        $this->_message('div', array('class' => 'alert alert-block alert-'.$this->message['type'].' fade in'));
      }
    }
  }

  public function message() {
    $this->_button(array('type' => 'button', 'class' => 'close', 'data-dismiss' => 'alert'));
    $this->_heading('h4');
    if (isset($this->message['text'])) $this->_text('p');
    if (isset($this->message['items'])) $this->_ul();
  }

  public function button() {
    echo '&times;';
  }

  public function heading() {
    echo self::escape($this->message['title']);
  }

  public function text() {
    echo self::escape($this->message['text']);
  }

  public function ul() {
    foreach ($this->message['items'] as $this->item) {
      $this->_li();
    }
  }

  public function li() {
    echo self::escape($this->item);
  }

  public function pre() {
    echo self::escape($this->item);
  }

  public function script() {
?>
$(function() {
  $('.alert').alert();
});
<?php
  }
}