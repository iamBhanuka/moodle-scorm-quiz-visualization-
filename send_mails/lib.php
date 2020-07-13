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
                echo html_writer::start_tag('table', array('style'=>'border:1px solid black'));
                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black; text-align:center'));
                                        echo " Course name ";
                                echo html_writer::end_tag('td', array());
                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black; text-align:center'));
                                        echo " Resourse name ";
                                echo html_writer::end_tag('td', array());
                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black; text-align:center'));
                                        echo " Days ";
                                echo html_writer::end_tag('td', array());
                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black; text-align:center'));
                                        echo " Hours ";
                                echo html_writer::end_tag('td', array());
                        echo html_writer::end_tag('tr', array());
                        $t=time();
                        $new_date=date("Y-m-d h:i:s",$t);
                        $d= new DateTime($new_date);
                        $newDate=$d->modify('+1 days'); 
                        $sql0 = "SELECT * FROM {role_assignments} WHERE  userid='$userids';";
                        $res0 = $DB->get_records_sql($sql0);
                        foreach($res0 as $f=>$val){
                                $sql1 = "SELECT instanceid from {context} WHERE id='$val->contextid' AND contextlevel=50;";
                                $res1=  $DB->get_records_sql($sql1); 
                                foreach($res1 as $g=>$val){
                                        $sql2="SELECT id,fullname,idnumber FROM {course} WHERE id='$val->instanceid';";
                                        $res2=  $DB->get_records_sql($sql2); 
                                        foreach($res2 as $h=>$val){
                                                $courseid=$val->id;
                                                $cid=$val->idnumber;
                                                $name=$val->fullname;
                                                $sql3="SELECT id,instance,module FROM {course_modules} WHERE course='$courseid' ;";
                                                $data1=$DB->get_records_sql($sql3);
                                                        foreach($data1 as $a=>$value){
                                                                
                                                                
                                                                $names=$value->instance;
                                                                //$ids=$value->id;                                
                                                                $sql3="SELECT name FROM {modules} WHERE id='$value->module' AND name!='forum';";
                                                                $data3=$DB->get_records_sql($sql3);
                                                                // $sql121 = "SELECT id from {context} WHERE instanceid='$ids' AND  contextlevel=70;";
                                                                // $res = $DB->get_records_sql($sql12); 
                                                                
                                                                foreach($data3 as $a=>$value){                                        
                                                                        if($value->name=='assign'){
                                                                                $sql4="SELECT id,name,duedate FROM {assign} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->duedate);
                                                                                        $con=new DateTime($var);                                                                                        
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        // $number_of_days=$d->modify('+1 days'); 
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array()); 
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }
                                                                        };                        
                                                                        if($value->name=='scorm'){
                                                                                $sql4="SELECT id,name,timeclose FROM {scorm} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }
                                                                        };
                                                                        if($value->name=='quiz'){
                                                                                $sql4="SELECT id,name,timeclose FROM {quiz} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }
                                                                        };
                                                                        if($value->name=='lesson'){
                                                                                $sql4="SELECT id,name,deadline FROM {lesson} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->deadline);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }
                                                                        };
                                                                        if($value->name=='feedback'){
                                                                                $sql4="SELECT id,name,timeclose FROM {feedback} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                       // echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }                                              
                                                                        };
                                                                        if($value->name=='choice'){
                                                                                $sql4="SELECT id,name,timeclose FROM {choice} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->timeclose);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
                                                                                }                                            
                                                                        };
                                                                        if($value->name=='assignment'){
                                                                                $sql4="SELECT id,name,timedue FROM {assignment} WHERE id='$names' AND course='$courseid';";
                                                                                $data4=$DB->get_records_sql($sql4);
                                                                                foreach($data4 as $e=>$value){
                                                                                        $var=date("Y-m-d h:i:s",$value->timedue);
                                                                                        $con=new DateTime($var);
                                                                                        $def= $newDate->diff($con);
                                                                                        echo html_writer::start_tag('tr', array('style'=>'border:1px solid black'));
                                                echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                echo $cid.' '.$name;
                                                echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $value->name.'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%Y-%M-%D").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::start_tag('td', array('style'=>'border:1px solid black'));
                                                                                        echo $def->format("%H:%I:%S").'<br>';
                                                                                        echo html_writer::end_tag('td', array());
                                                                                        echo html_writer::end_tag('tr', array());
                                                                                        //echo $value->name.'---->'.$def->format("%Y-%M-%D %H:%I:%S").'<br>';
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
        