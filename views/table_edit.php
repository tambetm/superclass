<?php
namespace views;

use views\Table;
use core\URL;
use core\Messages;
use core\Log;

class TableEdit extends Table {

  protected $db;
  protected $primary_key;
  protected $where;
  protected $defaults;
  protected $selectors;

  public function __construct($model) {
    parent::__construct($model);
    $this->primary_key = $model->primary_key();
    $this->db = $model->db();
  }

  public function post() {
    $table = $this->model->table();
    if (!isset($_POST[$table]['selector']) || !is_array($_POST[$table]['selector'])) {
      Messages::alert(_('You did not choose any rows.'));
      URL::redirect(URL::current_url());
    }

    if (!isset($_POST[$table]['data']) || !is_array($_POST[$table]['data']) 
      || !isset($_POST[$table]['where']) || !is_array($_POST[$table]['where'])) {
      Log::error('No data posted');
      exit;
    }

    $this->selectors = $_POST[$table]['selector'];
    $this->data = $_POST[$table]['data'];
    $this->where = $_POST[$table]['where'];
    $updates = 0;
    $inserts = 0;
    $deletes = 0;
    $success = true;
    $this->db->begin();
    foreach ($this->selectors as $nr => $operation) {
      $row = $this->data[$nr];
      $prefix = sprintf(_('Row %d: '), $nr + 1);
      switch($operation) {

        case 'insert':
          if ($this->model->validate($row, $prefix)) {
            $inserts += $this->model->insert($row);
          } else {
            $success = false;
          }
          break;

        case 'update':
          if ($this->model->validate($row, $prefix)) {
            $where = $this->where[$nr];
            $updates += $this->model->update($row, $where);
          } else {
            $success = false;
          }
          break;

        case 'delete':
          $where = $this->where[$nr];
          $deletes += $this->model->delete($where);
          break;

        default:
          Log::error('Unknown operation '.$operation);
          exit;
      }
    }

    if ($success) {
      $this->db->commit();
      if ($inserts > 0) Messages::success_item(sprintf(ngettext('%d record inserted.', '%d records inserted.', $inserts), $inserts));
      if ($updates > 0) Messages::success_item(sprintf(ngettext('%d record updated.', '%d records updated.', $updates), $updates));
      if ($deletes > 0) Messages::success_item(sprintf(ngettext('%d record deleted.', '%d records deleted.', $deletes), $deletes));
      Messages::success(_('Changes saved successfully.'));
      URL::redirect(URL::current_url());
    } else {
      $this->db->rollback();
      Messages::error(_('Error saving changes.'));
    }
  }

  public function title() {
    return sprintf(_('%s edit'), parent::title());
  }

  public function render() {
    $this->_form(array('action' => URL::current_url(), 'method' => 'post'));
    $this->_script(array('type' => 'text/javascript'));
  }

  protected function form() {
    parent::render();
    $this->_save_button(array('type' => 'submit', 'class' => 'btn btn-primary'));
  }

  protected function save_button() {
    echo self::escape(_('Save changes'));
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

  protected function table_tbody_tr() {
    $this->_table_tbody_tr_selector_td();
    parent::table_tbody_tr();
    $this->_table_tbody_tr_delete_td();
  }

  protected function table_tbody_tr_selector_td() {
    $attributes = array(
      'id' => $this->model->table().'__selector__'.$this->nr,
      'type' => 'checkbox',
      'name' => $this->model->table().'[selector]['.$this->nr.']',
      'value' => 'update',
      'class' => 'selector',
    );
    if (isset($this->selectors[$this->nr])) {
      $attributes['checked'] = 'checked';
    }
    $this->_input($attributes);
  }

  protected function table_tbody_tr_delete_td() {
    $attributes = array(
      'id' => $this->model->table().'__delete__'.$this->nr,
      'type' => 'button',
      'class' => 'btn btn-danger delete',
    );
    $this->_delete_button($attributes);
  }

  protected function table_tbody_tr_td() {
    if (isset($this->primary_key[$this->field])) {
      $this->_input(array(
        'type' => 'hidden', 
        'name' => $this->model->table().'[where]['.$this->nr.']['.$this->field.']', 
        'value' => $this->row[$this->field],
      ));
    }
    $field = $this->model->field($this->field);
    $name = $this->model->table().'[data]['.$this->nr.']['.$this->field.']';
    $value = $this->row[$this->field];
    $field->control($name, $value, array('class' => 'control'));
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
    foreach($this->meta as $this->field => $this->field_meta) {
      $this->_table_prototype_tr_td();
    }
    $this->_table_prototype_tr_delete_td();
  }

  protected function table_prototype_tr_selector_td() {
    $attributes = array(
      'id' => $this->model->table().'__selector____prototype__',
      'type' => 'checkbox',
      'name' => $this->model->table().'[selector][__prototype__]',
      'value' => 'insert',
      'class' => 'selector',
      'checked' => 'checked',
    );
    $this->_input($attributes);
  }

  protected function table_prototype_tr_delete_td() {
    $attributes = array(
      'id' => $this->model->table().'__delete____prototype__',
      'type' => 'button',
      'class' => 'btn btn-danger delete',
    );
    $this->_delete_button($attributes);
  }

  protected function table_prototype_tr_td() {
    $field = $this->model->field($this->field);
    $name = $this->model->table().'[data][__prototype__]['.$this->field.']';
    $value = $this->defaults[$this->field];
    $field->control($name, $value, array('class' => 'control'));
  }

  protected function table_addnew_tr() {
    $columns = count($this->meta) + 2;
    $this->_table_addnew_tr_td(array('colspan' => $columns));
  }

  protected function table_addnew_tr_td() {
    $this->_table_addnew_button(array('type' => 'button', 'id' => 'addnew', 'class' => 'btn'));
  }

  protected function table_addnew_button() {
    echo self::escape(_('Add new'));
  }

  protected function script() {
    ?>
$('#selectall').click(function() {
  var checked = $(this).prop('checked');
  $('#<?php echo $this->model->table()?> .selector').prop('checked', checked);
});

$('#addnew').click(function() {
  $.get('<?php echo URL::current_url()?>;add_new', function(data) {
    var count = $('#<?php echo $this->model->table()?> tbody tr').size();
    data = data.replace('__prototype__', count, 'g');
    $('#<?php echo $this->model->table()?> tbody').append(data);
  });
});

$('#<?php echo $this->model->table()?>').on('click', '.delete', function() {
  var $row = $(this).parent().parent();
  var $selector = $row.children().first().children().first();
  var operation = $selector.val();
  if (operation == 'update') {
    $selector.val('delete').prop('checked', true);
  } else {
    $selector.prop('checked', false);
  }
  $row.fadeOut('fast');
});

$('#<?php echo $this->model->table()?>').on('change', '.control', function() {
  var $row = $(this).parent().parent();
  var $selector = $row.children().first().children().first();
  $selector.prop('checked', true);
});

    <?php
  }
}
