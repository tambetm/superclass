<?php

class controllers_Home extends core_BaseController {

  public function index() {
    $model = new models_Yhistud();
    $view = new views_Table($model);
    $page = new pages_Bootstrap(array('main' => $view));
    $page->render();
  }
}
