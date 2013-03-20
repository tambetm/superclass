<?php

interface core_Model {
  public function __construct($table);
  public function meta();
  public function get();
  /*
  public function select($where = $_GET);
  public function insert($data = $_POST);
  public function update($data = $_POST, $where = $_GET);
  public function delete($where = $_POST);

  public function validate($data = $_POST);
  */
}
