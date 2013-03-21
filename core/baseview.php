<?php

abstract class core_BaseView extends core_HTMLTemplate implements interfaces_View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

}
