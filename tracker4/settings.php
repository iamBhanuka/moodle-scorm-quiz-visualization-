<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_tracker4'),
            get_string('descconfig', 'block_tracker4')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'tracker4/Allow_HTML',
            get_string('labelallowhtml', 'block_tracker4'),
            get_string('descallowhtml', 'block_tracker4'),
            '0'
        ));