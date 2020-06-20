<?php

            require_once(dirname(__FILE__) . '/../../config.php');

    // get page context according to id
    function block_tracker_course_context($courseid) { 
            if (class_exists('context_course')) {
                    return context_course::instance($courseid);
            } else {
                    return get_context_instance(CONTEXT_COURSE, $courseid);
            }
    }

    // selcect category id
//     function get_course_data( $id,$type,$uyear,$semester,$ndays,$action){ 
            
//             echo 'Selected Section: ';
//             echo $id.'  '; echo $type.'  ';echo $uyear.'  '; echo $semester.' '.$action.' ';

//             global $DB,$sid,$sc;

//             $sc=0;   
//             $subject=array();                   
            
//             $sql=  "SELECT id FROM {course_categories} WHERE parent
//                     IN(SELECT id FROM {course_categories} WHERE parent 
//                     IN(SELECT id FROM {course_categories} WHERE  parent 
//                         IN(SELECT id FROM {course_categories}  WHERE name='$id')AND name='$type')AND name='$uyear')
//                     AND name='$semester';";

//             $categorys=$DB->get_records_sql($sql);
//             if(count($categorys)>0){
//                     foreach($categorys as $top=>$value){
//                             $sid=$value->id;
//                             echo $value->id.':';
//                     }
//                     echo $ndays.'<br>';
            
//                     $sql1="SELECT id FROM {course} WHERE category='$sid';";
//                     $course=$DB->get_records_sql($sql1);
//                     foreach($course as $sub=>$value){
//                             $subject[$sc]=$value->id;
//                             $sc++;
//                     }                        
//                     get_login_data($subject,$ndays,$action); 
//             }
//             else{
//                     echo   'there is no subjects'.'<br>';
//             }                   
//     };

//     //draw graph according to views of subject 
//     function get_login_data($s,$ndays,$action){
//             global $DB,$countuser,$X, $OUTPUT,$name;
        
//             $countuser=0;
//             $name='';  
//             $dan=$ndays-1;
//             $data=array();
//             $labe2=array();
//             $days='-'.$dan.'days';
//             $date=date("Y-m-d");
//             $d = new DateTime($date);
//             $d->modify($days);
//             for($i=0;$i<=$dan;$i++){                                  
//                     $date=$d->format('d-m-Y');    
//                     $newDate = date("d M Y", strtotime($date));
//                     $new_date = date('dS F Y', strtotime($newDate));
//                     $labe2[$i]=$new_date;
//                     $d->modify('+1 days');                                
//             }                               
                    
//             $chart = new \core\chart_line();   
//             $cours=$DB->get_records_sql('SELECT id,fullname,idnumber FROM {course}');

//             foreach($s as $a){
//                     $X=0;
//                     foreach($labe2 as $date){

//                             if($action=='viewed'){
//                                     $sql6= "SELECT COUNT(userid) AS 'countusers'
//                                             FROM {logstore_standard_log} 
//                                             WHERE action='viewed' AND courseid=$a 
//                                             AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$date';";
//                                     $login6=$DB->get_records_sql($sql6); 
                            
//                                     foreach($login6 as $f=>$va){                                        
//                                             $data[$X]=$va->countusers;                                                                                                        
//                                     } 
//                                     $X++; 
//                             }else{
//                                     $sql6= "SELECT COUNT(userid) AS 'countusers'
//                                             FROM {logstore_standard_log} 
//                                             WHERE courseid=$a 
//                                             AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$date';";
//                                     $login6=$DB->get_records_sql($sql6); 
                            
//                                     foreach($login6 as $f=>$va){                                        
//                                             $data[$X]=$va->countusers;                                                                                                        
//                                     } 
//                                     $X++; 
                                    
                                    
//                             }
                                                        
//                     } 
//                     foreach ($cours as $o=>$valu){
//                             if($valu->id==$a){
//                                     $name=$valu->fullname.' ( '.$valu->idnumber.' )' ;
//                             }
//                     }
//                 $series = new \core\chart_series($name, $data);
//                 $chart->add_series($series);                      
//             } 

//             $chart->set_labels($labe2);
//             $yaxis = $chart->get_yaxis(0, true);
//             $yaxis->set_label('number of views');
//             $yaxis->set_stepsize(max(1, round(max($series) / 10)));
            
//             echo $OUTPUT->render($chart);                  
//     }



