<?php
namespace views;

class HelloWorld {
  public function title() {
    return 'My first app';
  }

  public function get() {
  }

  public function render() {
    echo 'Hello world!';
  }
}
