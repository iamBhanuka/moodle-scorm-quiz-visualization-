<?php

        require_once(dirname(__FILE__) . '/../../config.php');
        

       // get page content
        function block_sendmail_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }

        function get_all_couses($userids){
                global $DB;
                echo html_writer::start_tag('table');
                        echo html_writer::start_tag('tr', array('style'=>'font-weight:bold'));
                                echo html_writer::start_tag('td');
                                        echo " Course name ";
                                echo html_writer::end_tag('td');
                                echo html_writer::start_tag('td');
                                        echo " Resourse name ";
                                echo html_writer::end_tag('td');
                                echo html_writer::start_tag('td');
                                        echo " Days  (m d) ";
                                echo html_writer::end_tag('td');
                                echo html_writer::start_tag('td');
                                        echo " Hours (h m s) ";
                                echo html_writer::end_tag('td');
                        echo html_writer::end_tag('tr');
                        $t=time();
                        $new_date=date("Y-m-d h:i:s",$t);
                        $newDate=new DateTime($new_date);
                        //$newDate=$d->modify('+1 days'); 
                        $sql0="SELECT * FROM {role_assignments} WHERE  userid='$userids';";
                        $res0=$DB->get_records_sql($sql0);
                        foreach($res0 as $f=>$val){
                                $sql1="SELECT instanceid from {context} WHERE id='$val->contextid' AND contextlevel=50;";
                                $res1=$DB->get_records_sql($sql1); 
                                foreach($res1 as $g=>$val){
                                        $sql2="SELECT id,fullname,idnumber FROM {course} WHERE id='$val->instanceid';";
                                        $res2=$DB->get_records_sql($sql2); 
                                        foreach($res2 as $h=>$val){
                                                $courseid=$val->id;
                                                $cid=$val->idnumber;
                                                $name=$val->fullname;
                                                $sql3="SELECT id,instance,module FROM {course_modules} WHERE course='$courseid' ;";
                                                $data1=$DB->get_records_sql($sql3);
                                                foreach($data1 as $a=>$value){                                                              
                                                        $names=$value->instance;
                                                        $sql3="SELECT name FROM {modules} WHERE id='$value->module' AND name!='forum';";
                                                        $data3=$DB->get_records_sql($sql3);
                                                        foreach($data3 as $a=>$value){                                        
                                                                if($value->name=='assign'){
                                                                        $sql4="SELECT id,name,duedate FROM {assign} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->duedate);
                                                                                $con=new DateTime($var);                                                                                        
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }
                                                                };                        
                                                                if($value->name=='scorm'){
                                                                        $sql4="SELECT id,name,timeclose FROM {scorm} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }
                                                                };
                                                                if($value->name=='quiz'){
                                                                        $sql4="SELECT id,name,timeclose FROM {quiz} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                                
                                                                        }
                                                                };
                                                                if($value->name=='lesson'){
                                                                        $sql4="SELECT id,name,deadline FROM {lesson} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->deadline);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }
                                                                };
                                                                if($value->name=='feedback'){
                                                                        $sql4="SELECT id,name,timeclose FROM {feedback} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }                                              
                                                                };
                                                                if($value->name=='choice'){
                                                                        $sql4="SELECT id,name,timeclose FROM {choice} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }                                            
                                                                };
                                                                if($value->name=='assignment'){
                                                                        $sql4="SELECT id,name,timedue FROM {assignment} WHERE id='$names' AND course='$courseid';";
                                                                        $data4=$DB->get_records_sql($sql4);
                                                                        foreach($data4 as $e=>$value){
                                                                                $var=date("Y-m-d h:i:s",$value->timedue);
                                                                                $con=new DateTime($var);
                                                                                $def= $newDate->diff($con);
                                                                                if($newDate<$con){
                                                                                        
                                                                                        echo html_writer::start_tag('tr');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $cid.' '.$name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $value->name;
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%M  %D");
                                                                                                echo html_writer::end_tag('td');
                                                                                                echo html_writer::start_tag('td');
                                                                                                        echo $def->format("%H:%I:%S");
                                                                                                echo html_writer::end_tag('td'); 
                                                                                        echo html_writer::end_tag('tr');
                                                                                }
                                                                                
                                                                        }                                                 
                                                                };  
                                                        }                                                               
                                                }                                                         
                                        }
                                        echo '<br>';
                                }          
                        }                        
                echo html_writer::end_tag('table').'<br>';              
        }
        