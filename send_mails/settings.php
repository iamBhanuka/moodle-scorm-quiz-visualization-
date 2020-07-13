<?php
    $settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig','block_sendmail'),
        get_string('descconfig','block_sendmail')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'myblock/Allow_HTML',
        get_string('labelallowhtml','block_sendmail'),
        get_string('descallowhtml','block_sendmail'),
        '0'
    ));