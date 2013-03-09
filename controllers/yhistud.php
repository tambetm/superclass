<?php

class controllers_Yhistud extends core_BaseController {

  function index() {
    echo 'Hello World!';
    echo $this->context->url;
  }

}
