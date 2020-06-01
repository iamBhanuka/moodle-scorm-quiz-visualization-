<?php
 $settings->add(new admin_setting_heading(
    'headerconfig',
    get_string('headerconfig','block_coursegraph'),
    get_string('descconfig','block_coursegraph')
 ));

$settings->add(new admin_setting_configcheckbox(
    'coursegraph/Allow_HTML',
    //get_string('labelallowhtml','block_coursegraph'),
    get_string('descallowhtml','block_coursegraph'),
    '0'
));