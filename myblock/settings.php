<?php
    $settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig','block_myblock'),
        get_string('descconfig','block_mybock')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'myblock/Allow_HTML',
        get_string('labelallowhtml','block_myblock'),
        get_string('descallowhtml','block_myblock'),
        '0'
    ));