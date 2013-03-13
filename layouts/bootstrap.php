<?php

class layouts_Bootstrap extends layouts_HTML5 {

  public $viewport = 'width=device-width, initial-scale=1.0';

  public function head() {
    $this->_base(array('href' => core_URL::base_url()));
    parent::head();
  }

  public function title() {
    echo 'Bootstrap, from Twitter';
  }

  public function head_metas() {
    parent::head_metas();
    $this->_meta(array('name' => 'viewport', 'content' => $this->viewport));
  }

  public function head_links() {
    parent::head_links();
    $this->_link(array('href' => 'assets/css/bootstrap.min.css', 'rel' => 'stylesheet', 'media' => 'screen'));
  }

  public function body() {
    $this->_navbar('div', array('class' => 'navbar navbar-inverse navbar-static-top'));
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
    $this->_navbar_brand('a', array('class' => 'brand', 'href' => core_URL::base_url()));
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
    $model = new models_GlobalMenu();
    $view = new views_Nav($model);
    $view->render();
  }

  public function body_content() {
    $this->_title('h1');
    $this->content();
  }

  public function body_scripts() {
    parent::body_scripts();
    $this->_script(array('src' => 'http://code.jquery.com/jquery.js'));
    $this->_script(array('src' => 'assets/js/bootstrap.min.js'));
  }

}
