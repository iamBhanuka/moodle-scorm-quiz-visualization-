<?php
// require_once(dirname(__FILE__) . '/../../config.php');

// function get_courseid($courseid,$userid){
//     global $DB,$CFG;
//     $course_list=array();
//         $i=0;
//     $course = $DB->get_records_sql("SELECT id FROM {quiz} WHERE course=$courseid");
//     foreach($course as $record_r=>$new_n)
//         {
//             $course_list[$i]=$new_n->id;
//             $i++; 
            
//         }

//         $grades=grade($userid, $course_list);
//         return $grades;


// }

// function course_names($userid,$courseid){
//     global $DB,$CFG;
//     $course_list2=array();
//         $i=0;
//     $course = $DB->get_records_sql("SELECT DISTINCT name FROM {quiz} WHERE course=$courseid");
//     foreach($course as $record_r=>$new_n)
//         {
//             $course_list2[$i]=$new_n->name;
//             $i++; 
            
//         }
        
//        return  $course_list2;
// }

// function grade($userid, $course_list){
//     global $DB,$CFG;
//     $grade_list=array();
//         $i=0;
//         foreach( $course_list as $list)
//         {
       
//             $grade = $DB->get_records_sql("SELECT id,userid,quiz,grade FROM {quiz_grades} WHERE userid=$userid AND quiz  =$list");
//             foreach($grade as $record_r=>$new_n)
//             {
//                 $grade_list[$i]=$new_n->grade;
//                 $i++; 
//             }
            
//         }
//         return $grade_list;
// }