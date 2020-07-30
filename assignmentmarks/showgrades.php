<?php
require_once(dirname(__FILE__) . '/../../config.php');

// function get_course_name($courseid){
//     global $DB,$CFG;
   
//         $course=$DB->get_records_sql("SELECT fullname FROM {course} WHERE id=$courseid"); 
   
//         foreach ($course as $c=>$fullname) {
//                 $name =$fullname->fullname;
                           
//         }
//         return $name;
// }

// function get_user_name($userid){
//     global $DB,$CFG;
   
//         $course=$DB->get_records_sql("SELECT firstname,lastname FROM {user} WHERE id=$userid"); 
   
//         foreach ($course as $c=>$fullname) {
//                 $name =$fullname->firstname." ".$fullname->lastname;
                           
//         }
//         return $name;
// }

function get_assignment($courseid,$userid){
    global $DB,$CFG;
    $assign_list=array();
        $i=0;

        // $course=$DB->get_records_sql("SELECT fullname FROM {course} WHERE id=$courseid"); 
   
        // foreach ($course as $c=>$fullname) {
        //         $name =$fullname->fullname;
                           
        // }
    // get available assignment
    $assign = $DB->get_records_sql("SELECT id FROM {assign} WHERE course=$courseid  ");
    foreach($assign as $record_r=>$new_n)
        {
           
            $assign_list[$i]=$new_n->id;
             $i++; 
            
        }
        $a=get_assignmentid($assign_list,$userid);
        return $a;


}

function get_assignmentid($assign_list,$userid){
    global $DB,$CFG;
    $course_list=array();
        $i=0;

     foreach( $assign_list as $list)
     {
   
    $course = $DB->get_records_sql("SELECT assignment,id FROM {assign_submission} WHERE userid=$userid AND assignment=$list ");
    
    foreach($course as $record_r=>$new_n)
        {
           
            $course_list[$i]=$new_n->assignment;
            //echo "<br>";
            $i++; 
            
        }
      }

        $grades=grade($courseid,$userid,$assign_list);
        return $grades;


}

// function assignment_names($userid,$courseid){
//     global $DB,$CFG;
//     $course_list2=array();
//         $i=0;
//     $course = $DB->get_records_sql("SELECT DISTINCT ass.name FROM {assign} as ass  INNER JOIN {assign_grades} as ag ON ass.id=ag.assignment 
//     AND ass.course=$courseid  AND ag.userid=$userid AND ag.grade>=0");
//     foreach($course as $record_r=>$new_n)
//         {
//             $course_list2[$i]=$new_n->name;
//             $i++; 
            
//         }
        
//        return  $course_list2;
// }

function grade($courseid,$userid,$assign_list){
    global $DB,$CFG;
    $grade_list=array();
        $i=0;
        
        foreach( $assign_list as $list)
        {
            
            $course0 = $DB->get_records_sql("SELECT assignment,id,userid,grade,grader FROM {assign_grades} WHERE  userid=$userid AND assignment=$list  ");
            foreach($course0 as $record_r=>$new_n)
            {
                $grade_list[$i]=$new_n->grade;
                //echo "<br>";
               $i++;
                
            }
            
         }
        return $grade_list;
        
}