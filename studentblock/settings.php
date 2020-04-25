<?php
    $settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig','block_studentblock'),
        get_string('descconfig','block_studentblock')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'studentblock/Allow_HTML',
        get_string('labelallowhtml','block_studentblock'),
        get_string('descallowhtml','block_studentblock'),
        '0'
    ));