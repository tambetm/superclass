<?php

class core_Database {

  var $config;
  var $connection;

  function __construct($name) {
    include('config/database.php');
    $this->config = $config[$name];

    $connection_string = '';
    if (is_array($this->config)) {
      foreach ($this->config as $name => $value) {
        if ($connection_string != '') $connection_string .= ' ';
        $connection_string .= "$name=$value";
      }
    }
    
    $this->connection = pg_connect($connection_string);
  }

  function meta_data($table) {
    return pg_meta_data($this->connection, $table);
  }

  function query($sql) {
    $res = pg_query($this->connection, $sql);
    if ($res) {
      return pg_fetch_all($res);
    } else {
      return false;
    }
  }

  function select($table, $where = array()) {
    if (count($where) == 0) {
      return $this->query("select * from $table");
    } else {
      return pg_select($this->connection, $table, $where);
    }
  }

  function __destruct() {
    pg_close($this->connection);
  }

  static $databases = array();

  static function database($name = 'default') {
    if (isset(self::$databases[$name])) {
      return self::$databases[$name];
    } else {
      return self::$databases[$name] = new core_Database($name);      
    }
  }
}
