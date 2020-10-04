<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/blocks/assignmentmarks/showgrades.php');

function block_assignmentmarks_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}


function assignment($userid,$courseid){
    
        global  $OUTPUT,$CFG;
        $marks=get_assignment_ass($courseid,$userid);
        $course=assignment_names_ass($userid,$courseid);
        //set max as 50 to set step size as 5
        $max=50;
        
        $chart = new \core\chart_line();
        $series = new \core\chart_series("marks", $marks);
        $chart->add_series($series);
        $chart->set_labels($course);
        $chart->set_title(get_course_name_ass($courseid). " assignment results of ".get_user_name_ass($userid));;
        $yaxis = $chart->get_yaxis(0, true);
        $yaxis->set_label('Assignments Marks');
        $yaxis->set_stepsize(max(1, round($max /10)));
        echo $OUTPUT->render($chart);

}





}




