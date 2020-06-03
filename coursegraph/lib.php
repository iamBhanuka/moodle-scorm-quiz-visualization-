<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/blocks/coursegraph/showaccess.php');
//require_once($CFG->dirroot.'/blocks/coursegraph/showgrades.php');

function block_coursegraph_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}


// function quiz($userid,$courseid){
    
//         global $DB,$CFG, $OUTPUT;
//         $marks=get_courseid($courseid,$userid);
//         $course=course_names($userid,$courseid);
 //          $array=get_student_id_array();
        
        
//         $chart = new \core\chart_line();
//         //for($i=0;$i<count($course);$i++){
//             $series = new \core\chart_series("marks", $marks);
//             $chart->add_series($series);
//         //}
//         $chart->set_labels($course);
//         $chart->set_title("Your quizes results for this subject");;
//         $yaxis = $chart->get_yaxis(0, true);
//         $yaxis->set_label('Marks');
//         $yaxis->set_stepsize(max(1, round($max / 10)));
//         echo $OUTPUT->render($chart);

// }


// function get_activity_details($userid,$courseid){
//     global  $USER, $COURSE, $CFG, $OUTPUT, $DB,$countu;
//     $k=0;
//     $chart = new \core\chart_line();
//     $course_loga=get_date_options();
    
//     $subjects=block_graph_get_enrol_course($userid,$courseid);
//     for($r=0;$r<count($subjects);$r++)
//     {
//          $subjects[$r] ;
//     }
//     $names=get_course_names($subjects);
//     for($r=0;$r<count($names);$r++)
//     {
//          $names[$r] ;
//     }
    
//     foreach($subjects as $list)
//         {
//             echo '<br>';
//             $x=0;
            
//             foreach( $course_loga as $da)
//             {
//                    $sql5= "SELECT  COUNT(action) AS 'countu',courseid
//                             FROM {logstore_standard_log} 
//                             WHERE  userid=$userid  AND action='viewed' AND courseid =$list AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y') ='$da'
//                           "; 
//                     $login5=$DB->get_records_sql($sql5);
//                     foreach($login5 as $record_r=>$new_n)
//                         {
//                             $c_id[$x]=$new_n->countu;   
//                         } 
//                         $x++;

//             }
            
          
//             $series = new \core\chart_series($names[$k], $c_id);
//             $chart->add_series($series);
//             $k++;
//             if($max<=max($c_id)){
//                 $max= max($c_id);
//             }
            
//         }

// $chart->set_labels($course_loga);
// $yaxis = $chart->get_yaxis(0, true);
// $yaxis->set_label('number of actoins');
// $yaxis->set_stepsize(max(1, round($max / 10)));

// echo $OUTPUT->render($chart);
 


// }



