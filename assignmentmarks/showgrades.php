<?php
require_once(dirname(__FILE__) . '/../../config.php');

// get course name
function get_course_name_ass($courseid){
    global $DB,$CFG;
   
        $course=$DB->get_records_sql("SELECT fullname FROM {course} WHERE id=$courseid"); 
   
        foreach ($course as $c=>$fullname) {
                $name =$fullname->fullname;
                           
        }
        return $name;
}

function get_user_name_ass($userid){
    global $DB,$CFG;
   
        $course=$DB->get_records_sql("SELECT firstname,lastname FROM {user} WHERE id=$userid"); 
   
        foreach ($course as $c=>$fullname) {
                $name =$fullname->firstname." ".$fullname->lastname;
                           
        }
        return $name;
}

// get assignment ids

function get_assignment_ass($courseid,$userid){
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
        $a=get_assignmentid_ass($assign_list,$userid);
        return $a;


}

//get submitted assignment
function get_assignmentid_ass($assign_list,$userid){
    global $DB,$CFG;
    $course_list=array();
        $i=0;

     foreach( $assign_list as $list)
     {
   
    $course = $DB->get_records_sql("SELECT assignment,id FROM {assign_submission} WHERE userid=$userid AND assignment=$list ");
    
    foreach($course as $record_r=>$new_n)
        {
           
            $course_list[$i]=$new_n->assignment;
            
            $i++; 
            
        }
      }

        $grades=grade_ass($courseid,$userid,$assign_list);
        return $grades;


}

// get assignment names
function assignment_names_ass($userid,$courseid){
    global $DB,$CFG;
    $course_list2=array();
        $i=0;
    $course = $DB->get_records_sql("SELECT DISTINCT ass.name FROM {assign} as ass 
                                    INNER JOIN {assign_grades} as ag ON ass.id=ag.assignment 
                                    AND ass.course=$courseid AND ag.userid=$userid AND ag.grade>=0"  );
                                    foreach($course as $record_r=>$new_n)
        {
            $course_list2[$i]=$new_n->name;
            $i++; 
            
        }
        
       return  $course_list2;
}

//get assignment results
function grade_ass($courseid,$userid,$assign_list){
    global $DB,$CFG;
    $grade_list=array();
        $i=0;
        
        foreach( $assign_list as $list)
        {
            //  grade>=0 some times shows grade as -1
            $course0 = $DB->get_records_sql("SELECT assignment,id,userid,grade,grader FROM {assign_grades} 
                                            WHERE  userid=$userid AND assignment=$list AND grade>=0 ");
            foreach($course0 as $record_r=>$new_n)
            {
                $grade_list[$i]=$new_n->grade;
               $i++;
                
            }
            
         }
        return $grade_list;
        
}