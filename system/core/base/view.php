<?php
namespace core\base;

use core\HTML as _HTML;
use helpers\Request;
use helpers\Response;
use helpers\Arrays;
use helpers\Messages;

abstract class View extends _HTML implements \core\interfaces\View {

  protected $model;
  protected $db;
  protected $model_name;

  protected $data;
  protected $where;
  protected $selectors;
  protected $operations;
  protected $row_status;
  protected $redirect = null;

  public function __construct($model) {
    $this->model = $model;
    $this->db = $model->db();
    $this->model_name = $model->name();
  }

  public function get() {
    $this->data = $this->model->select($_GET);
  }

  public function post() {
    $table = Request::post($this->model_name);
    if (!is_array($table)) {
      throw new \InvalidArgumentException("No data posted to table '{$this->model_name}'");
    }

    $this->selectors = Arrays::get($table, 'selector');
    $this->operations = Arrays::get($table, 'operation');
    $this->data = Arrays::get($table, 'data');
    $this->where = Arrays::get($table, 'where');;

    if (!is_array($this->selectors)) {
      Messages::alert(_('You did not choose any rows.'));
      Response::redirect();
    }

    $updates = 0;
    $inserts = 0;
    $deletes = 0;
    $success = true;
    $this->db->begin();
    foreach ($this->selectors as $nr => $selected) {
      $row =& $this->data[$nr];
      $operation = $this->operations[$nr];
      Messages::item_row($nr, sprintf(_('Record %d: '), $nr + 1));
      switch($operation) {

        case 'insert':
          if ($this->model->validate($row)) {
            $result = $this->model->insert($row);
            $inserts += $result;
          } else {
            $result = false;
          }
          break;

        case 'update':
          if ($this->model->validate($row)) {
            $where = $this->where[$nr];
            $result = $this->model->update($row, $where);
            $updates += $result;
          } else {
            $result = false;
          }
          break;

        case 'delete':
          $where = $this->where[$nr];
          $result = $this->model->delete($where);
          $deletes += $result;
          break;

        default:
          throw new \InvalidArgumentException("Unknown operation '$operation'");
      }
      if (!$result) $this->row_status[$nr] = 'error';
      $success = $success && $result;
    }
    Messages::item_row(null);

    if ($success) {
      $this->db->commit();
      if ($inserts > 0) Messages::success_item(sprintf(ngettext('%d record inserted.', '%d records inserted.', $inserts), $inserts));
      if ($updates > 0) Messages::success_item(sprintf(ngettext('%d record updated.', '%d records updated.', $updates), $updates));
      if ($deletes > 0) Messages::success_item(sprintf(ngettext('%d record deleted.', '%d records deleted.', $deletes), $deletes));
      Messages::success(_('Changes saved successfully.'));
      Response::redirect($this->redirect);
    } else {
      $this->db->rollback();
      Messages::error(_('Error saving changes.'));
    }
  }
}
