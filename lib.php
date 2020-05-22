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

    $sql_courses =  "SELECT C.* FROM {role_assignments} as A INNER JOIN {context} as B on A.contextid=B.id INNER JOIN {course} as C on C.id=B.instanceid AND B.contextlevel=50 AND A.roleid='3'  AND A.userid='$userid' ;";
    $sql_courses_res = $DB->get_records_sql($sql_courses);
    $has_course = false;