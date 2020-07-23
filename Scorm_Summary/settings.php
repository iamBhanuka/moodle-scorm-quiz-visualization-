<?php
    $settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig','block_scormsum'),
        get_string('descconfig','block_scormsum')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'scormsum/Allow_HTML',
        get_string('labelallowhtml','block_scormsum'),
        get_string('descallowhtml','block_scormsum'),
        '0'
    ));