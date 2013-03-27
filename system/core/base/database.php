<?php
namespace core\base;

use helpers\String;
use helpers\Config;

abstract class Database implements \interfaces\Database {

  static $databases = array();

  static public function database($name = 'default') {
    if (isset(self::$databases[$name])) {
      return self::$databases[$name];
    } else {
      Config::load($config, "config/databases/$name.php");
      if (!is_array($config)) {
        throw new \InvalidArgumentException("Configuration for database '$name' is invalid");
      }

      if (!isset($config['driver'])) {
        throw new \InvalidArgumentException("Missing driver for database '$name'");
      }
      $driver = $config['driver'];
      unset($config['driver']);

      $driver_class = Resolver::get_database_class($driver);
      return self::$databases[$name] = new $driver_class($config);
    }
  }

  protected $config;

  public function __construct($config) {
    $this->config = $config;
    $this->connect();
  }

  // have to be implemented by drivers
  //abstract public function escape($value, $type);
  abstract protected function connect();
  abstract protected function query($sql);
  abstract protected function query_params($sql, $params);
  abstract protected function fetch_all($result);
  abstract protected function fetch_row($result);
  abstract protected function affected_rows($result);

  protected function extract_schema($table) {
    if (String::contains($table, '.')) {
      return explode('.', $table);
    } else {
      return array('public', $table);
    }
  }

  // might need to be reimplemented by drivers
  public function columns($table) {
    list($schema, $table) = $this->extract_schema($table);
    $result = $this->query_params('select * from information_schema.columns where table_schema = $1 and table_name = $2', array($schema, $table));
    if ($result === false) return false;
    return $this->fetch_hashed_rows($result, 'column_name');
  }

  // might need to be reimplemented by drivers
  public function primary_key($table) {
    list($schema, $table) = $this->extract_schema($table);
    $result = $this->query_params('select kcu.* from information_schema.key_column_usage kcu join information_schema.table_constraints tc using (constraint_catalog, constraint_schema, constraint_name) where kcu.table_schema = $1 and kcu.table_name = $2 and tc.constraint_type = $3', array($schema, $table, 'PRIMARY KEY'));
    if ($result === false) return false;
    return $this->fetch_hashed_rows($result, 'column_name');
  }

  // helper
  protected function fetch_hashed_rows($result, $field) {
    $hash = array();
    while ($row = $this->fetch_row($result)) {
      $hash[$row[$field]] = $row;
    }
    return $hash;
  }

  // for inserts, updates and deletes, returns affected rows
  public function execute($sql) {
    $result = $this->query($sql);
    if ($result === false) return false;
    return $this->affected_rows($result);
  }

  public function select_all($sql) {
    $result = $this->query($sql);
    if ($result === false) return false;
    return $this->fetch_all($result);
  }

  public function select_row($sql) {
    $result = $this->query($sql);
    if ($result === false) return false;
    return $this->fetch_row($result);
  }

  public function begin() {
    return $this->execute('begin');
  }

  public function commit() {
    return $this->execute('commit');
  }

  public function rollback() {
    return $this->execute('rollback');
  }
}
