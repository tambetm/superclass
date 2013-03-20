<?php

class models_Yhistud extends core_BaseModel {

  function __construct($table = 'yhistud') {
    parent::__construct($table);
    $this->meta = array_intersect_key($this->meta, array('yhistu_id' => 1, 'nimi' => 1, 'tyyp' => 1));
  }
}
