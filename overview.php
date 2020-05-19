<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/blocks/myscorm/lib.php');
$extention_array = array('jpg','png','jpeg','gif','ico');
define('USER_SMALL_CLASS', 20);   
define('USER_LARGE_CLASS', 200);  
define('DEFAULT_PAGE_SIZE', 20);
define('SHOW_ALL_PAGE_SIZE', 5000);

$id       = required_param('myscormid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);