<?php

class actions_Updatemany extends core_BaseAction {

  public function process() {
    $table = $this->model->table();
    if (!isset($_POST[$table]) || !is_array($_POST[$table])) {
      // no data posted
      echo 'NO DATA'; // TODO
      exit;
    }
    foreach ($_POST[$table] as $nr => $row) {
      if (!isset($row['data']) || !isset($row['where'])) {
        echo 'NO DATA';
        exit;
      }
      $data = $row['data'];
      $where = $row['where'];
      $this->model->update($data, $where);
    }
    core_URL::redirect('table');
  }
}