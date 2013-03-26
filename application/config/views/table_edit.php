<?php

$config['attributes'] = array('class' => 'table table-bordered table-hover');
$config['table_actions'] = array(
  'save' => array('button', array('type' => 'submit', 'class' => 'btn btn-primary'), _('Save changes')),
  'back' => array('a', array('href' => 'table', 'class' => 'btn'), _('Back'))
);
