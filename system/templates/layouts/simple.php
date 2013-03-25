<?php
namespace templates\layouts;

use templates\layouts\Bootstrap;
use helpers\URL;
use templates\blocks\Menu;
use templates\blocks\Messages;

class Simple extends Bootstrap {

  public function body() {
    $this->_navbar('div', array('class' => 'navbar navbar-inverse navbar-static-top'));
    $this->_primary_menu('div', array('class' => 'container'));
    parent::body();
  }

  public function navbar() {
    $this->_navbar_inner('div', array('class' => 'navbar-inner'));
  }

  public function navbar_inner() {
    $this->_navbar_container('div', array('class' => 'container'));
  }

  public function navbar_container() {
    $this->_navbar_button(array('type' => 'button', 'class' => 'btn btn-navbar', 'data-toggle' => 'collapse', 'data-target' => '.nav-collapse'));
    $this->_navbar_brand('a', array('class' => 'brand', 'href' => URL::base_path()));
    $this->_navbar_menu('div', array('class' => 'nav-collapse collapse'));
  }

  public function navbar_button() {
    $this->_span(array('class' => 'icon-bar'));
    $this->_span(array('class' => 'icon-bar'));
    $this->_span(array('class' => 'icon-bar'));
  }

  public function navbar_brand() {
    echo 'Subclass';
  }

  public function navbar_menu() {
    $menu = new Menu('global');
    $menu->render();
  }

  public function body_content() {
    $this->messages();
    $this->_title('h1');
    $this->content();
  }

  public function primary_menu() {
    $menu = new Menu('primary');
    $menu->render();
  }

  public function messages() {
    $messages = new Messages();
    $messages->render();
  }

  public function content() {
    $this->view->render();
  }
}
