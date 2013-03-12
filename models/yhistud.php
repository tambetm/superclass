<?php

class models_Yhistud extends core_BaseModel {

  function __construct($table = 'yhistud') {
    parent::__construct($table);
    $this->meta_data = array_intersect_key($this->meta_data, array('yhistu_id' => 1, 'nimi' => 1, 'tyyp' => 1));
  }
}
