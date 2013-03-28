<?php
namespace core\interfaces;

interface View extends Template {
  public function __construct($model);
  public function get();
  public function post();
  public function title();
}
