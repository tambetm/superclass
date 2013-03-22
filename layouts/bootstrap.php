<?php
namespace layouts;

use layouts\HTML5Layout;
use core\URL;
use models\GlobalMenu;
use views\Menu;
use views\Messages;

class Bootstrap extends HTML5Layout {

  public $viewport = 'width=device-width, initial-scale=1.0';

  public function head() {
    $this->_base(array('href' => URL::base_url()));
    parent::head();
  }

  public function title() {
    echo self::escape($this->view->title());
  }

  public function head_metas() {
    parent::head_metas();
    $this->_meta(array('name' => 'viewport', 'content' => $this->viewport));
  }

  public function head_links() {
    parent::head_links();
    $this->_link(array('href' => 'assets/css/bootstrap.min.css', 'rel' => 'stylesheet', 'media' => 'screen'));
  }

  public function head_scripts() {
    parent::head_scripts();
    $this->_script(array('src' => 'http://code.jquery.com/jquery.js'));
    $this->_script(array('src' => 'assets/js/bootstrap.min.js'));
  }

  public function body() {
    $this->_navbar('div', array('class' => 'navbar navbar-inverse navbar-static-top'));
    $this->_primary_menu('div', array('class' => 'container'));
    $this->_body_content('div', array('class' => 'container', 'id' => 'content'));
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
    echo 'Project name';
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
