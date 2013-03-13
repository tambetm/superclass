<?php

abstract class core_BaseView extends core_HTMLTemplate implements core_View {

  protected $model;

  public function __construct($model) {
    $this->model = $model;
  }

}
