<?php
namespace templates\layouts;

use templates\layouts\HTML5Layout;
use helpers\URL;

class Bootstrap extends HTML5Layout {

  public $viewport = 'width=device-width, initial-scale=1.0';

  public function head_metas() {
    parent::head_metas();
    $this->_meta(array('name' => 'viewport', 'content' => $this->viewport));
  }

  public function head_links() {
    parent::head_links();
    $this->_link(array('href' => URL::base_path().'assets/css/bootstrap.min.css', 'rel' => 'stylesheet', 'media' => 'screen'));
  }

  public function head_scripts() {
    parent::head_scripts();
    $this->_script(array('src' => 'http://code.jquery.com/jquery.js'));
  }

  public function body() {
    $this->_body_content('div', array('class' => 'container', 'id' => 'content'));
    parent::body();
  }

  public function body_content() {
    $this->view->render();
  }

  public function body_scripts() {
    $this->_script(array('src' => URL::base_path().'assets/js/bootstrap.min.js'));
  }

}
