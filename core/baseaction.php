<?php

abstract class core_BaseAction implements interfaces_Action {
  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }
}
