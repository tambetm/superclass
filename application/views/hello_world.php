<?php
namespace views;

use core\View;

class HelloWorld extends View {
  public function title() {
    return 'My first app';
  }
  
  public function render() {
    echo 'Hello world!';
  }
}
