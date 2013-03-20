<?php

class core_Database {

  static $databases = array();

  static public function database($name = 'default') {
    if (isset(self::$databases[$name])) {
      return self::$databases[$name];
    } else {
      return self::$databases[$name] = new core_Database($name);      
    }
  }

  protected $config;
  protected $connection;

  public function __construct($name) {
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
/*
  public function meta_data($table) {
    return pg_meta_data($this->connection, $table);
  }
*/
  public function meta_data($table) {
    if (strpos($table, '.') !== false) {
      list($schema, $table) = explode('.', $table);
    } else {
      $schema = 'public';
    }

    $result = pg_query_params($this->connection, 'select * from information_schema.columns where table_schema = $1 and table_name = $2', array($schema, $table));
    return $this->fetch_hashed_rows($result, 'column_name');
  }

  protected function fetch_hashed_rows($result, $field) {
    $hash = array();
    while ($row = pg_fetch_assoc($result)) {
      $hash[$row[$field]] = $row;
    }
    return $hash;
  }

  public function escape($value, $type = 'varchar') {
    if (is_null($value)) {
      return 'null';
    }
    switch ($type) {
      case 'int2':
      case 'int4':
      case 'int8':
      case 'smallint':
      case 'integer':
      case 'bigint':
      case 'serial':
      case 'bigserial':
        return strval(intval($value));

      case 'real':
      case 'double precision':
        return strval(floatval($value));

      case 'bytea':
        return "'".pg_escape_bytea($this->connection, $value)."'";

      case 'decimal':
      case 'numeric':
        // TODO: better check for numeric, what to do when not?
        //return is_numeric($value) ? strval($value) : 'null';
      case 'varchar':
      case 'char':
      case 'text':
      case 'money':
      case 'timestamp':
      case 'timestamp with time zone':
      case 'date':
      case 'time':
      case 'interval':
      case 'boolean':
      default:
        return "'".pg_escape_string($this->connection, $value)."'";
    }
  }

  public function query($sql) {
    $res = pg_query($this->connection, $sql);
    if ($res) {
      return pg_fetch_all($res);
    } else {
      return false;
    }
  }

  public function select($table, $where = array()) {
    if (count($where) == 0) {
      return $this->query("select * from $table");
    } else {
      return pg_select($this->connection, $table, $where);
    }
  }

  public function __destruct() {
    pg_close($this->connection);
  }
}
