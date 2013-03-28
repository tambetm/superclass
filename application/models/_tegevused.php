<?php

$config['caption'] = _('Tegevused');
$config['columns']['tegevuse_id']['label'] = _('Tegevuse ID');
$config['columns']['ylemtegevuse_id']['label'] = _('Ülemtegevus');
$config['columns']['ylemtegevuse_id']['data_type'] = 'lookup';
$config['columns']['ylemtegevuse_id']['sql'] = 'select tegevuse_id, kood from tegevused order by ylemtegevuse_id, jrk_nr';
$config['columns']['kood']['label'] = _('Kood');
$config['columns']['jrk_nr']['label'] = _('Jrk nr');
$config['columns']['systeemne']['label'] = _('Süsteemne');
$config['columns']['korteripohine']['label'] = _('Korteripõhine');
