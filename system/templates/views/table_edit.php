<?php
namespace templates\views;

use templates\views\Table;
use helpers\URL;
use helpers\Messages;
use helpers\Response;
use helpers\Arrays;
use helpers\Config;

class TableEdit extends Table {

  protected $db;
  protected $where;
  protected $defaults;
  protected $selectors;
  protected $operations;
  protected $row_status;

  public function __construct($model) {
    parent::__construct($model);
    $this->db = $model->db();

    Config::load($this->config, 'config/views/table_edit.php');
    Config::load($this->config, VIEW_NAMESPACE.DIRECTORY_SEPARATOR."_{$this->model_name}_table_edit.php");
  }

  public function post() {
    $table = Arrays::get($_POST, $this->model_name);
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
      Messages::item_prefix(sprintf(_('Row %d: '), $nr + 1));
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
    Messages::item_prefix('');

    if ($success) {
      $this->db->commit();
      if ($inserts > 0) Messages::success_item(sprintf(ngettext('%d record inserted.', '%d records inserted.', $inserts), $inserts));
      if ($updates > 0) Messages::success_item(sprintf(ngettext('%d record updated.', '%d records updated.', $updates), $updates));
      if ($deletes > 0) Messages::success_item(sprintf(ngettext('%d record deleted.', '%d records deleted.', $deletes), $deletes));
      Messages::success(_('Changes saved successfully.'));
      Response::redirect();
    } else {
      $this->db->rollback();
      Messages::error(_('Error saving changes.'));
    }
  }

  public function title() {
    return sprintf(_('%s edit'), parent::title());
  }

  public function render() {
    $this->_form(array('action' => URL::self(), 'method' => 'post'));
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
  }

  protected function table_thead_tr_selector_td() {
    $attributes = array(
      'id' => 'selectall',
      'type' => 'checkbox',
    );
    $this->_input($attributes);
  }

  protected function table_thead_tr_actions() {
    $this->_table_thead_tr_delete_th();
  }

  protected function _table_tbody_tr() {
    $attributes = array();
    // make deleted records different colour
    $operation = Arrays::get($this->operations, $this->nr, 'update');
    $attributes['class'] = $operation;
    if (isset($this->row_status[$this->nr])) {
      $attributes['class'] .= ' '.$this->row_status[$this->nr];
    } elseif ($operation == 'delete') {
      // hide deleted row after validation error
      $attributes['class'] .= ' hide';
    }
    parent::_table_tbody_tr($attributes);
  }

  protected function table_tbody_tr() {
    $this->_table_tbody_tr_selector_td();
    parent::table_tbody_tr();
  }

  protected function table_tbody_tr_selector_td() {
    $attributes = array(
      'type' => 'checkbox',
      'name' => "{$this->model_name}[selector][{$this->nr}]",
      'value' => 't',
      'class' => 'selector',
    );
    if (isset($this->selectors[$this->nr])) {
      $attributes['checked'] = 'checked';
    }
    $this->_input($attributes);

    $attributes = array(
      'type' => 'hidden',
      'name' => "{$this->model_name}[operation][{$this->nr}]",
      'value' => isset($this->operations[$this->nr]) ? $this->operations[$this->nr] : 'update',
    );
    $this->_input($attributes);

    if ($this->operations[$this->nr] != 'insert' && is_array($this->primary_key)) {
      foreach($this->primary_key as $field => $dummy) {
        $this->_input(array(
          'type' => 'hidden', 
          'name' => "{$this->model_name}[where][{$this->nr}][{$field}]", 
          'value' => isset($this->where[$this->nr][$field]) ? 
            $this->where[$this->nr][$field] : 
            $this->row[$field],
        ));
      }
    }
  }

  protected function table_tbody_tr_actions() {
    $this->_table_tbody_tr_delete_td();
  }

  protected function table_tbody_tr_delete_td() {
    $this->_button(array('type' => 'button', 'class' => 'btn btn-small btn-danger delete'), _('Delete'));
  }

  protected function table_tbody_tr_td() {
    $name = "{$this->model_name}[data][{$this->nr}][{$this->field}]";
    $value = $this->row[$this->field];
    if ($this->field_meta->is_updatable()) {
      $this->field_meta->control($name, $value, array('class' => 'control'));
    } else {
      $this->_input(array('type' => 'hidden', 'name' => $name, 'value' => $value));
      $this->field_meta->output($value);
    }
  }

  protected function table_tfoot() {
    parent::table_tfoot();
    $this->_table_addnew_tr();
  }

  protected function table_addnew_tr() {
    $columns = count($this->fields) + 2;
    $this->_table_addnew_tr_td(array('colspan' => $columns));
  }

  protected function table_addnew_tr_td() {
    $this->_button(array('type' => 'button', 'id' => 'addnew', 'class' => 'btn btn-small'), _('Add new'));
  }

  protected function table_actions() {
    $this->_button(array('type' => 'submit', 'class' => 'btn btn-primary'), _('Save changes'));
    $this->_a(array('href' => URL::self('table'), 'class' => 'btn'), _('Back'));
  }

  public function add_new() {
    $this->nr = '__prototype__';
    $this->operations[$this->nr] = 'insert';
    $this->selectors[$this->nr] = 't';
    $this->row = $this->model->defaults();
    $this->_table_tbody_tr();
    exit;
  }

  protected function script() {
?>
$(function() {
  $('#selectall').click(function() {
    var checked = $(this).prop('checked');
    $('#<?=$this->model_name?> .selector').prop('checked', checked);
  });

  $('#addnew').click(function() {
    $.get('<?=URL::self($_GET + array('_action' => 'add_new'))?>', function(data) {
      var count = $('#<?=$this->model_name?> tbody tr').size();
      data = data.replace('__prototype__', count, 'g');
      $('#<?=$this->model_name?> tbody').append(data);
    });
  });

  $('#<?=$this->model_name?>').on('click', '.delete', function() {
    var $row = $(this).parent().parent();
    var $selector = $row.children().first().children().first();
    var $operation = $selector.next();
    var operation = $operation.val();
    switch(operation) {
    case 'update':
      // if existing record, then mark for deleting
      $operation.val('delete');
      $selector.prop('checked', true);
      $row.removeClass('update').addClass('delete');
      $row.fadeOut('fast');
      break;
    case 'insert':
      // if new record, then just cancel insert
      //$selector.prop('checked', false);
      //$row.removeClass('insert').addClass('delete');
      $row.fadeOut('fast', function() {$row.remove();});
      break;
    case 'delete':
      // on second deletion just ensure that this row is selected
      $selector.prop('checked', true);
      $row.fadeOut('fast');
      break;
    }
  });

  $('#<?=$this->model_name?>').on('change', '.control', function() {
    var $row = $(this).parent().parent();
    var $selector = $row.children().first().children().first();
    $selector.prop('checked', true);
  });
});
<?php
  }
}
