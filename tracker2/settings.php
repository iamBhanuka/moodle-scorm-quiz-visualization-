<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_tracker2'),
            get_string('descconfig', 'block_tracker2')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'tracker2/Allow_HTML',
            get_string('labelallowhtml', 'block_tracker2'),
            get_string('descallowhtml', 'block_tracker2'),
            '0'
        ));