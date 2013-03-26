<?php

$config['caption'] = _('Tegevused');
$config['columns']['tegevuse_id']['label'] = _('Tegevuse ID');
$config['columns']['ylemtegevuse_id']['label'] = _('Ülemtegevus');
$config['columns']['ylemtegevuse_id']['class'] = 'types\Lookup';
$config['columns']['ylemtegevuse_id']['lookup_value'] = 'tegevuse_id';
$config['columns']['ylemtegevuse_id']['lookup_label'] = 'kood';
$config['columns']['ylemtegevuse_id']['lookup_table'] = 'tegevused';
$config['columns']['ylemtegevuse_id']['lookup_order_by'] = array('ylemtegevuse_id' => null);
$config['columns']['ylemtegevuse_id']['lookup_order_by'] = array('ylemtegevuse_id' => 'asc', 'jrk_nr' => 'asc');
$config['columns']['kood']['label'] = _('Kood');
$config['columns']['jrk_nr']['label'] = _('Jrk nr');
$config['columns']['systeemne']['label'] = _('Süsteemne');
$config['columns']['korteripohine']['label'] = _('Korteripõhine');
