<?php
namespace templates\views;

use templates\views\Table;
use helpers\URL;
use helpers\Messages;
use helpers\Response;
use helpers\Arrays;
use helpers\Config;

class TableEdit extends Table {

  protected $where;
  protected $defaults;
  protected $selectors;
  protected $operations;

  public function __construct($model) {
    parent::__construct($model);
    $this->config['primary_key'] = $model->primary_key();

    Config::load($this->config, 'config/views/table_edit.php');
    Config::load($this->config, VIEW_NAMESPACE.DIRECTORY_SEPARATOR.$model->table().'_table_edit_meta.php');
  }

  public function post($params) {
    $table = Arrays::get($params, $this->table);
    if (!is_array($table)) {
      throw new \InvalidArgumentException("No data posted to table '$this->table'");
    }

    $this->selectors = Arrays::get($table, 'selector');
    $this->operations = Arrays::get($table, 'operation');
    $this->data = Arrays::get($table, 'data');
    $this->where = Arrays::get($table, 'where');;

    if (!is_array($this->selectors)) {
      Messages::alert(_('You did not choose any rows.'));
      Response::redirect(URL::current_url());
    }

    $updates = 0;
    $inserts = 0;
    $deletes = 0;
    $success = true;
    $this->db->begin();
    foreach ($this->selectors as $nr => $selected) {
      $row = $this->data[$nr];
      $operation = $this->operations[$nr];
      Messages::item_prefix(sprintf(_('Row %d: '), $nr + 1));
      switch($operation) {

        case 'insert':
          if ($this->model->validate($row)) {
            $result = $this->model->insert($row);
            $success = $success && $result;
            $inserts += $result;
          } else {
            $success = false;
          }
          break;

        case 'update':
          if ($this->model->validate($row)) {
            $where = $this->where[$nr];
            $result = $this->model->update($row, $where);
            $success = $success && $result;
            $updates += $result;
          } else {
            $success = false;
          }
          break;

        case 'delete':
          $where = $this->where[$nr];
          $result = $this->model->delete($where);
          $success = $success && $result;
          $deletes += $result;
          break;

        default:
          throw new \InvalidArgumentException("Unknown operation '$operation'");
      }
    }
    Messages::item_prefix('');

    if ($success) {
      $this->db->commit();
      if ($inserts > 0) Messages::success_item(sprintf(ngettext('%d record inserted.', '%d records inserted.', $inserts), $inserts));
      if ($updates > 0) Messages::success_item(sprintf(ngettext('%d record updated.', '%d records updated.', $updates), $updates));
      if ($deletes > 0) Messages::success_item(sprintf(ngettext('%d record deleted.', '%d records deleted.', $deletes), $deletes));
      Messages::success(_('Changes saved successfully.'));
      Response::redirect(URL::current_url());
    } else {
      $this->db->rollback();
      Messages::error(_('Error saving changes.'));
    }
  }

  public function title() {
    return sprintf(_('%s edit'), parent::title());
  }

  public function render() {
    $this->_form(array('action' => URL::current_path(), 'method' => 'post'));
    $this->_script(array('type' => 'text/javascript'));
  }

  protected function form() {
    parent::render();
  }

  protected function _table_colgroup() {
    $this->_table_selector_col(array('class' => 'selector'));
    parent::_table_colgroup();
    $this->_table_delete_col(array('class' => 'delete'));
  }

  protected function table_thead_tr() {
    $this->_table_thead_tr_selector_td();
    parent::table_thead_tr();
    $this->_table_thead_tr_delete_td();
  }

  protected function table_thead_tr_selector_td() {
    $attributes = array(
      'id' => 'selectall',
      'type' => 'checkbox',
    );
    $this->_input($attributes);
  }

  protected function table_thead_tr_delete_td() {
  }

  protected function delete_button() {
    echo self::escape(_('Delete'));
  }

  protected function _table_tbody_tr() {
    $attributes = array();
    if ($this->operations[$this->nr] == 'delete') {
      // make deleted records different colour
      $attributes['class'] = 'error';
    }
    parent::_table_tbody_tr($attributes);
  }

  protected function table_tbody_tr() {
    $this->_table_tbody_tr_selector_td();
    parent::table_tbody_tr();
    $this->_table_tbody_tr_delete_td();
  }

  protected function table_tbody_tr_selector_td() {
    $attributes = array(
      'type' => 'checkbox',
      'name' => "$this->table[selector][$this->nr]",
      'value' => 't',
      'class' => 'selector',
    );
    if (isset($this->selectors[$this->nr])) {
      $attributes['checked'] = 'checked';
    }
    $this->_input($attributes);

    $attributes = array(
      'type' => 'hidden',
      'name' => "$this->table[operation][$this->nr]",
      'value' => isset($this->operations[$this->nr]) ? $this->operations[$this->nr] : 'update',
    );
    $this->_input($attributes);
  }

  protected function table_tbody_tr_delete_td() {
    $attributes = array(
      'id' => $this->table.'__delete__'.$this->nr,
      'type' => 'button',
      'class' => 'btn btn-small btn-danger delete',
    );
    $this->_delete_button($attributes);
  }

  protected function table_tbody_tr_td() {
    if (isset($this->config['primary_key'][$this->field])) {
      $this->_input(array(
        'type' => 'hidden', 
        'name' => "$this->table[where][$this->nr][$this->field]", 
        'value' => $this->row[$this->field],
      ));
    }
    $name = "$this->table[data][$this->nr][$this->field]";
    $value = $this->row[$this->field];
    $this->field_meta->control($name, $value, array('class' => 'control'));
  }

  protected function table_tfoot() {
    parent::table_tfoot();
    $this->_table_addnew_tr();
  }

  public function add_new() {
    $this->_table_prototype_tr();
  }

  protected function table_prototype_tr() {
    $this->defaults = $this->model->defaults();
    $this->_table_prototype_tr_selector_td();
    foreach($this->fields as $this->field => $this->field_meta) {
      $this->_table_prototype_tr_td();
    }
    $this->_table_prototype_tr_delete_td();
  }

  protected function table_prototype_tr_selector_td() {
    $attributes = array(
      'type' => 'checkbox',
      'name' => "$this->table[selector][__prototype__]",
      'value' => 't',
      'class' => 'selector',
      'checked' => 'checked',
    );
    $this->_input($attributes);

    $attributes = array(
      'type' => 'hidden',
      'name' => "$this->table[operation][__prototype__]",
      'value' => 'insert',
    );
    $this->_input($attributes);
  }

  protected function table_prototype_tr_delete_td() {
    $attributes = array(
      'type' => 'button',
      'class' => 'btn btn-small btn-danger delete',
    );
    $this->_delete_button($attributes);
  }

  protected function table_prototype_tr_td() {
    $name = "$this->table[data][__prototype__][$this->field]";
    $value = $this->defaults[$this->field];
    $this->field_meta->control($name, $value, array('class' => 'control'));
  }

  protected function table_addnew_tr() {
    $columns = count($this->fields) + 2;
    $this->_table_addnew_tr_td(array('colspan' => $columns));
  }

  protected function table_addnew_tr_td() {
    $this->_table_addnew_button(array('type' => 'button', 'id' => 'addnew', 'class' => 'btn btn-small'));
  }

  protected function table_addnew_button() {
    echo self::escape(_('Add new'));
  }

  protected function script() {?>
$('#selectall').click(function() {
  var checked = $(this).prop('checked');
  $('#<?=$this->table?> .selector').prop('checked', checked);
});

$('#addnew').click(function() {
  $.get('<?=URL::current_path()?>;add_new', function(data) {
    var count = $('#<?=$this->table?> tbody tr').size();
    data = data.replace('__prototype__', count, 'g');
    $('#<?=$this->table?> tbody').append(data);
  });
});

$('#<?=$this->table?>').on('click', '.delete', function() {
  var $row = $(this).parent().parent();
  var $selector = $row.children().first().children().first();
  var $operation = $selector.next();
  var operation = $operation.val();
  switch(operation) {
  case 'update':
    // if existing record, then mark for deleting
    $operation.val('delete');
    $selector.prop('checked', true);
    break;
  case 'insert':
    // if new record, then just cancel insert
    $selector.prop('checked', false);
    break;
  case 'delete':
    // just ignore second deletion, we will hide the row below
    break;
  }
  $row.fadeOut('fast');
});

$('#<?=$this->table?>').on('change', '.control', function() {
  var $row = $(this).parent().parent();
  var $selector = $row.children().first().children().first();
  $selector.prop('checked', true);
});<?php
  }
}
