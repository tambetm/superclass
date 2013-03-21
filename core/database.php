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

  protected function extract_schema($table) {
    if (strpos($table, '.') !== false) {
      return explode('.', $table);
    } else {
      return array('public', $table);
    }
  }
  
  public function meta_data($table) {
    list($schema, $table) = $this->extract_schema($table);
    $result = pg_query_params($this->connection, 'select * from information_schema.columns where table_schema = $1 and table_name = $2', array($schema, $table));
    return $this->fetch_hashed_rows($result, 'column_name');
  }

  public function primary_key($table) {
    list($schema, $table) = $this->extract_schema($table);
    $result = pg_query_params($this->connection, 'select kcu.* from information_schema.key_column_usage kcu join information_schema.table_constraints tc using (constraint_catalog, constraint_schema, constraint_name) where kcu.table_schema = $1 and kcu.table_name = $2 and tc.constraint_type = $3', array($schema, $table, 'PRIMARY KEY'));
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
    if (is_null($value) || $value === '') {
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

  protected function query($sql) {
    return pg_query($this->connection, $sql);
  }

  public function execute($sql) {
    $result = $this->query($sql);
    return pg_affected_rows($result);
  }

  public function select($sql) {
    $result = $this->query($sql);
    if (!$result) return false;
    return pg_fetch_all($result);
  }

  public function __destruct() {
    pg_close($this->connection);
  }
}
