<?php

class controllers_Home extends core_BaseController {

  public function index() {
    $model = new models_Yhistud();
    $view = new views_Table($model);
    $layout = new layouts_Bootstrap(array(DEFAULT_REGION => $view));
    $layout->render();
  }
}
