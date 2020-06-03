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

    foreach($sql_courses_res as $record=>$course){
        if($has_course == false){
            $has_course = $courseid == $course->id;
        }
    }
    if($has_course){


        $sql_scorm= "SELECT id,name FROM {scorm} WHERE course=$courseid;";        
        $sql_scorm_res = $DB->get_records_sql($sql_scorm);

        
        echo '<select name="scorm" id="dd_scorm" onchange="scormSelect();">';
        echo "<option selected>Select Quiz</option>";

        $dropdown_scorm = "";

        foreach($sql_scorm_res as $scorm_res){
            $dropdown_scorm .= "<option value=\"" . $scorm_res->id . "\">" .$scorm_res->name . "</option>";
        }

        
        echo $dropdown_scorm;
        echo "</select>";

        echo "</br>";

        echo "<div id='scorm_data'></div>";

        // return;

        
        $data_for_dd_scorm = array();

        
        $sql_marks= "SELECT B.*,A.id as scorm_id FROM {scorm} as A INNER JOIN {scorm_scoes_track} as B on A.id=B.scormid AND A.course='$courseid' AND B.element='cmi.core.score.raw' AND B.value<50;";
        $sql_marks_res = $DB->get_records_sql($sql_marks);

        foreach($sql_marks_res as $record=>$mark){
            $userid = $mark->userid;
            $sql_user= "SELECT * FROM {user} WHERE id=$userid;";
            $sql_user_res=$DB->get_records_sql($sql_user);

            foreach($sql_user_res as $record=>$user){
                $data["userid"] = $user->id;
                $data["firstname"] = $user->firstname;
                $data["lastname"] = $user->lastname;
                $data["mark"] = $mark->value;
                $data["scorm_id"] = $mark->scorm_id;
                $data["attempt"] = $mark->attempt;
                array_push($data_for_dd_scorm,$data);
            }
        }