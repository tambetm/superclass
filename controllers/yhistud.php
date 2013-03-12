<?php

class controllers_Yhistud extends core_BaseController {

  function index() {
    $page = new pages_Bootstrap();
    $page->render();
  }

}
