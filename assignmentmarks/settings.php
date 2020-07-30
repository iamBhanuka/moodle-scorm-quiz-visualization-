<?php
$settings->add(new admin_setting_heading(
    'headerconfig',
    get_string('headerconfig','block_assignmentmarks'),
    get_string('descconfig','block_assignmentmarks')
));

$settings->add(new admin_setting_configcheckbox(
    'assignmentmarks/Allow_HTML',
    get_string('labelallowhtml','block_assignmentmarks'),
    get_string('descallowhtml','block_assignmentmarks'),
    '0'
));