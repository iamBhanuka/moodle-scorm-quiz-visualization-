<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_tracker3'),
            get_string('descconfig', 'block_tracker3')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'tracker3/Allow_HTML',
            get_string('labelallowhtml', 'block_tracker3'),
            get_string('descallowhtml', 'block_tracker3'),
            '0'
        ));