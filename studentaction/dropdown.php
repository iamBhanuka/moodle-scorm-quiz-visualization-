<?php

require_once(dirname(__FILE__) . '/../../config.php');

function block_graph_get_year_id($year){
    global $DB;
    
    $course_y = $DB->get_records_sql("SELECT id FROM {course_categories} WHERE name='$year' AND  parent ='0'");
    foreach($course_y as $record_r=>$new_n)
    {
        $year_id=$new_n->id;
       
    }
    //return $year_id;
}

function block_graph_get_course_id($course, $year_id){
    global $DB;
    $course_c = $DB->get_records_sql("SELECT id FROM {course_categories} WHERE name='$course' AND parent ='$year_id'");
    foreach($course_c as $record_r=>$new_n)
    {
        $course_id=$new_n->id;  
    }
    return $course_id;
}

 function block_graph_get_academic_year_id($academic_year,$course_id){
    global $DB;
    $course_c = $DB->get_records_sql("SELECT id FROM {course_categories} WHERE name='$academic_year' AND parent ='$course_id'");
    foreach($course_c as $record_r=>$new_n)
    {
        $academi_year_id=$new_n->id;
        
    }
    return $academi_year_id;
 }

function block_graph_get_semester_id($semester,$academic_year_id){
    global $DB;
    $course_sem = $DB->get_records_sql("SELECT id FROM {course_categories} WHERE name='$semester' AND parent ='$academic_year_id'");
    foreach($course_sem as $record_r=>$new_n)
    {
        $semester_id=$new_n->id;
    
    }
    return $semester_id;
}

function block_graph_get_selected_course($semester_id){
    global $DB,$course_list;
    $course_list=array();
    $i=0;
    $course = $DB->get_records_sql("SELECT id FROM {course} WHERE category ='$semester_id'");
    
    foreach ($course as $c=>$fullname) {

        $course_list[$i] =$fullname->id;
        $i++;   
                   
}
    return $course_list;
}

 //get courses names from course table
function get_course_names($semester_id ){  

    global $CFG,$DB,$i;
    $label=array();
    $i=0;
   
    $course=$DB->get_records_sql("SELECT fullname,startdate,shortname FROM {course} WHERE category ='$semester_id'"); 
   
    foreach ($course as $c=>$fullname)
    {
        $label[$i] =$fullname->fullname;
        $i++;              
    }
  
    return $label;
};

function get_date_options($day) {
    global $SITE,$month,$DB,$datesr;
    $days =$day;
    
    $date =date('jS M Y');
    $d= new DateTime($date);
    $dates=array();
    for($i=0;$i<$days;$i++)
    {
        //$a=strtotime(yesterday);
        //echo date('d-M-Y',mktime(date("d")-1));
         $dates[$i] =$d->format('jS F Y');
         $d->modify('-1 day');

    }
     echo'<br>';
    for($i=0;$i<$days;$i++){
        $datesr=array_reverse($dates);
        $log['labels'][$i]=$datesr[$i];
    }
    
    
    return $log['labels'];
}





