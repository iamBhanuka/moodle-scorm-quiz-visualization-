<?php

require_once(dirname(__FILE__) . '/../../config.php');
function block_myscorm_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}

function get_login_datas($courseid,$userid ){

    global $DB;