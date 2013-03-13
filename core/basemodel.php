<?php

class core_BaseModel implements core_Model {

  var $table;
  var $db;
  var $meta_data;

  function __construct($table) {
    $this->table = $table;
    $this->db = core_Database::database();
    $this->meta_data = $this->db->meta_data($table);
  }

  function meta() {
    return $this->meta_data;
  }

  function data() {
    $where = array_intersect_key($_GET, $this->meta_data);
    return $this->db->select($this->table, $where);
  }
}
