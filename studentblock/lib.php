<?php

        require_once(dirname(__FILE__) . '/../../config.php');

        function block_studentblock_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }

        function assginment($courseid, $userid ){
                global $DB,$i,$e,$r,$c1,$c2,$c3,$c4,$OUTPUT;
                $date=array();
                $id=array();
                $i=0;
                $r=0;
                $c1=0;
                $c2=0;
                $c3=0;
                $c4=0;
            
                $sql="SELECT id, name, course,DATE_FORMAT(FROM_UNIXTIME(duedate),'%D %M %Y') AS 'day',duedate 
                        FROM {assign}
                        WHERE course='$courseid';";
                $data=$DB->get_records_sql($sql);           
                foreach($data as $a => $value){
                        $id[$i]=$value->id;
                        $date[$i]=$value->duedate;
                        $c4++;
                        $i++;
                        
                }
                echo 'there are  '.$c4.' assignments'.'<br>';
            
                foreach($id as $b){                
                        $sql1="SELECT id,assignment,userid,DATE_FORMAT(FROM_UNIXTIME(timemodified),'%D %M %Y') AS 'day' ,timemodified 
                                FROM {assign_submission} WHERE assignment='$b' AND userid='$userid' ;";
                        $data1=$DB->get_records_sql($sql1);
                        foreach($data1 as $a => $value){
                                $e=$value->timemodified;
                                
                                if($date[$r]>$e){
                                        $c1++;
                                }
                        }
                        $r++;
                }
                $r=0;
                foreach($id as $b){                
                        $sql2="SELECT id,assignment,userid,DATE_FORMAT(FROM_UNIXTIME(timemodified),'%D %M %Y') AS 'day' ,timemodified 
                                FROM {assign_submission} WHERE assignment='$b' AND userid='$userid' ;";
                        $data2=$DB->get_records_sql($sql2);
                        foreach($data2 as $a => $value){
                                $e=$value->timemodified;
                                if($date[$r]<$e){
                                        $c2++;
                               }
                        }
                        $r++;
                }
                $sql3="SELECT id, name, course,DATE_FORMAT(FROM_UNIXTIME(duedate),'%D %M %Y') AS 'day',duedate 
                        FROM {assign}
                        WHERE course='$courseid' AND id NOT IN (SELECT assignment FROM {assign_submission} WHERE  userid='$userid');";
                $data3=$DB->get_records_sql($sql3);
           
                foreach($data3 as $a => $value){
                    $c3++;
                }

                $chart = new \core\chart_bar();  
                $series = new \core\chart_series('number of assignments', array($c1,$c2,$c3));
                $chart->add_series($series);    
                $chart->set_labels(array('submitted befor duedate','late submitions','not yet submit'));
                $yaxis = $chart->get_yaxis(0, true);
                $yaxis->set_stepsize(max(1,round( max($c1,$c2,$c3)) / 10));
                
                echo $OUTPUT->render($chart);   
        }