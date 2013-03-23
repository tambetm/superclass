<?php
namespace layouts;

use core\BaseLayout;
use core\Locale;

abstract class HTML5Layout extends BaseLayout {

  protected $locale;

  public function __construct($view) {
    parent::__construct($view);
    $this->locale = Locale::instance();
  }

  public function render() {
    $this->doctype();
    $this->_html(array('lang' => $this->locale->get_primary_language()));
  }

  public function doctype() {
    echo "<!DOCTYPE html>";
  }

  public function html() {
    $this->_head();
    $this->_body();
  }

  public function head() {
    $this->_title();
    $this->head_metas();
    $this->head_links();
    $this->head_scripts();
  }

  abstract public function title();

  public function head_metas() {
    $this->_meta(array('charset' => $this->locale->get_charset()));
  }

  public function head_links() {
  }

  public function head_scripts() {
  }

  public function body() {
    $this->body_scripts();
  }

  public function body_scripts() {
  }
}
