<?php
namespace core\interfaces;

interface Controller {
  public function __construct($model_name, $view_name, $action);
}
