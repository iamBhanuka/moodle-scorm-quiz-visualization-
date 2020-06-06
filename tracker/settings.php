<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_tracker'),
            get_string('descconfig', 'block_tracker')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'tracker/Allow_HTML',
            get_string('labelallowhtml', 'block_tracker'),
            get_string('descallowhtml', 'block_tracker'),
            '0'
        ));