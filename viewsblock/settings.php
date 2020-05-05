<?php
    $settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig','block_viewsblock'),
        get_string('descconfig','block_viewsblock')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'viewsblock/Allow_HTML',
        get_string('labelallowhtml','block_viewsblock'),
        get_string('descallowhtml','block_viewsblock'),
        '0'
    ));