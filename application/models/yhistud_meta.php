<?php

$config['caption'] = _('Ühistud');
$config['columns']['yhistu_id']['label'] = _('Ühistu ID');
$config['columns']['nimi']['label'] = _('Nimi');
$config['columns']['tyyp']['label'] = _('Tüüp');
$config['columns']['tyyp']['class'] = 'types\Enum';
$config['columns']['tyyp']['options'] = array(
  'T' => _('ühistu'),
  'S' => _('ühisus'),
  'F' => _('firma'),
  'H' => _('haldusfirma'),
);
