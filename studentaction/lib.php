<?php

 require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->dirroot.'/blocks/myblock/dropdown.php');


function block_studentaction_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}


//  function dropdown_selector_form($id,$courseid,$userid) {
 
 
//     global $selectedcourse,$selectedacademic_year,$selectedyear;
//     $max=0;
//     echo html_writer::start_tag('form', array('class' => 'selectform', 'method' => 'POST','action'=>"./overview.php"));
//     echo html_writer::start_div();
//     echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'logingraphid', 'value' => $id ));
//     echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid ));
//     echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'userid','value' => $userid ));
   
//         $option1=array(
//                         '2020'=>'2020',
//                         '2019'=>'2019'
//                     );
                    
//         $option2=array(
//                         'CS'=>'CS',
//                         'IS'=>'IS'
//                     );
                    
//         $option3=array(
//                         '1'=>'1 YEAR',
//                         '2'=>'2 YEAR',
//                         '3'=>'3 YEAR'
//                     );
                
//         $option4=array(
//                         '1'=>'1 SEM',
//                         '2'=>'2 SEM'
//                     );

//         $option5=array(
//                         '1'=>'30',
//                         '2'=>'60',
//                         '3'=>'90',
//                         '4'=>'105',
//                     );

//         $option6=array(
//                         '1'=>'viewed',
//                         '2'=>'All'
//                     );

//    // echo html_writer::label("year", 'year', false, array('class' => 'accesshide'));
//     echo html_writer::select($option1, "year1", $selectedyear,false);
//     //echo html_writer::label("course", 'course', false, array('class' => 'accesshide'));
//     echo html_writer::select($option2, "course1", $selectedcourse,false);
//     //echo html_writer::label("a_year", 'a_year', false, array('class' => 'accesshide'));
//     echo html_writer::select($option3, 'academic_year1', $selectedacademic_year ,false);
//     //echo html_writer::label("sem", 'sem', false, array('class' => 'accesshide'));
//     echo html_writer::select($option4, 'semester1', $selectedsemester,false);
//     echo html_writer::select($option5, 'period', $selectedperiod,false);
//     echo html_writer::select($option6, 'action', $selectedaction,false);
//     echo html_writer::start_div();
//         $selectedyear=  $option1[$_POST['year1']];
//         $selectedcourse=  $option2[$_POST['course1']];
//         $selectedacademic_year=  $option3[$_POST['academic_year1']];
//         $selectedsemester=  $option4[$_POST['semester1']];
//         $selectedperiod=  $option5[$_POST['period']];
//         $selectedaction=  $option6[$_POST['action']];
      
//     echo html_writer::end_div();
//     echo html_writer::empty_tag('input',array('type'=>'submit','value'=>'Graph','class' => 'btn btn-primary'));
//     //,'onclick'=>'report_log_print_graph($selectedyear,$selectedcourse,$selectedacademic_year,$selectedsemester)'
 
//     echo html_writer::end_div();
//     echo html_writer::end_tag('form');

//     report_log_print_graph($selectedyear,$selectedcourse,$selectedacademic_year,$selectedsemester,$selectedperiod,$selectedaction);
    
    
// }

// function report_log_print_graph($selectedyear,$selectedcourse,$selectedacademic_year,$selectedsemester,$selectedperiod,$selectedaction) { //draw line chart
//     global  $USER, $COURSE, $CFG, $OUTPUT, $DB,$count;
//     $k=0;
//     echo '<br>';
//     echo '<br>';
//     echo $selectedyear;
//     echo $selectedcourse;
//     echo $selectedacademic_year;
//     echo $selectedsemester;
//     echo $selectedperiod;
//     echo $selectedaction;

//    //select_course_path();
//     $year_id=block_graph_get_year_id($selectedyear);
//     $course_id=block_graph_get_course_id($selectedcourse, $year_id);
//     $academic_year_id=block_graph_get_academic_year_id($selectedacademic_year,$course_id);
//     $semester_id=block_graph_get_semester_id($selectedsemester,$academic_year_id);
//     $selected_courses=block_graph_get_selected_course($semester_id);
//     $chart = new \core\chart_line();
//     $names=get_course_names($semester_id);
    
//     $log['labels']=get_date_options($selectedperiod);
//         global $DB,$countuser;
        
        
//         foreach($selected_courses as $list)
//             {
//                 echo '<br>';
//                 $x=0;
                
//                 foreach( $log['labels'] as $da){
                    
//                     if($selectedaction=='viewed'){
                    
//                         $sql5= "SELECT  COUNT(userid) AS 'countusers',courseid
//                                 FROM {logstore_standard_log} 
//                                 WHERE action='viewed' AND courseid =$list AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y') ='$da'
//                               "; 
//                         $login5=$DB->get_records_sql($sql5);
//                         foreach($login5 as $record_r=>$new_n)
//                             {
//                                  $c_id[$x]=$new_n->countusers;
                                
                               
//                             } 
//                             $x++;
                            
//                     }
                    
                    
//                     else{
//                         $sql6= "SELECT  COUNT(userid) AS 'countusers'
//                                 FROM {logstore_standard_log} 
//                                 WHERE  courseid =$list AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$da'
//                               ;"; 
//                         $login6=$DB->get_records_sql($sql6);
//                         foreach($login6 as $record_r=>$new_n)
//                         {
//                             $c_id[$x]=$new_n->countusers;
                         
//                         } $x++;
                         
//                     }
//                 }
                
              
//                 $series = new \core\chart_series($names[$k], $c_id);
//                 $chart->add_series($series);
//                 $k++;
//                 if($max<=max($c_id)){
//                     $max= max($c_id);
//                 }
                
//             }

//     $chart->set_labels($log['labels']);
//     $yaxis = $chart->get_yaxis(0, true);
//     $yaxis->set_label('number of logins');
//     $yaxis->set_stepsize(max(1, round($max / 10)));
   
//     echo $OUTPUT->render($chart);
     
    

// }



