<?php

abstract class core_BaseView extends core_HTMLTemplate {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

}
