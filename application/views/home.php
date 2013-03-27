<?php
namespace views;

use core\View;

class Home extends View {
  public function title() {
    return 'My first app';
  }
  
  public function render() {
    echo 'Hello world!';
  }
}
