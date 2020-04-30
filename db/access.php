<?php
    $capabilities = array(
 
    'block/myscorm:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),
 
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/myscorm:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,