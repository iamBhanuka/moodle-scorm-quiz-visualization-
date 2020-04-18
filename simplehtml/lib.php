<?php

        require_once(dirname(__FILE__) . '/../../config.php');

        // get page context according to id
        function block_simplehtml_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }
         // draw graph according to views of subject 
         function get_logins_data($id2,$ndays,$action,$courseid){
            if($ndays>0 && $id2>0){
                    global $DB,$countuser,$X, $OUTPUT,$name,$max,$cat,$u,$ylabel;                
                    $u=0;  
                    $max=1;
                    $countuser=0;
                    $name='';
                    $subject=array();
                    $label=array();
                    $dan=$ndays-1;
                    $data=array();//for data
                    $labe2=array();//for dates label
                    $days='-'.$dan.'days';
                    $date=date("Y-m-d");
                    $d = new DateTime($date);
                    $d->modify($days);
                    for($i=0;$i<=$dan;$i++){                                  
                            $date=$d->format('d-m-Y');    
                            $newDate = date("d M Y", strtotime($date));
                            $new_date = date('jS F Y', strtotime($newDate));
                            $labe2[$i]=$new_date;
                            $d->modify('+1 days');                                
                    }                                     
                    $chart = new \core\chart_line();
                    $sql1="SELECT category FROM {course} WHERE id='$courseid';";  
                    $cours=$DB->get_records_sql($sql1);
                    foreach ($cours as $s=>$value){
                        $cat=$value->category;
                    }
                    $sql0="SELECT id,fullname, idnumber FROM {course} WHERE category='$cat';";  
                    $all=$DB->get_records_sql($sql0);   
                    $label=get_coursees($id2);
                    foreach($label as $i){                        
                            if($i==$courseid){                              
                                    foreach($label as $i){
                                            foreach ($all as $s=>$value ){
                                                    if($value->id==$i){
                                                             $subject[$u]=$i;
                                                             $u++; 
                                                    }
                                            } 
                                    } 
                                    $sql2="SELECT firstname, lastname FROM {user} WHERE id='$id2';";
                                    $l=$DB->get_records_sql($sql2);
                                    foreach($l as $w=>$va){
                                            echo $va->firstname.' '.$va->lastname.'<br>';                      
                                            foreach($subject as $a){
                                                    $X=0;
                                                    foreach($labe2 as $date){        
                                                            if($action=='viewed'){
                                                                    $sql6= "SELECT COUNT(userid) AS 'countusers'
                                                                            FROM {logstore_standard_log} 
                                                                            WHERE action='viewed' AND courseid=$a  AND userid='$id2'
                                                                            AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$date';";
                                                                    $login6=$DB->get_records_sql($sql6); 
                                                                    foreach($login6 as $f=>$va){                                        
                                                                            $data[$X]=$va->countusers;                                                                                                        
                                                                    } 
                                                                    $X++; 
                                                                    $ylabel='number of views';
                                                            }else{
                                                                    $sql6= "SELECT COUNT(userid) AS 'countusers'
                                                                            FROM {logstore_standard_log} 
                                                                            WHERE courseid=$a AND userid='$id2'
                                                                            AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$date';";
                                                                    $login6=$DB->get_records_sql($sql6);                                                        
                                                                    foreach($login6 as $f=>$va){                                        
                                                                            $data[$X]=$va->countusers;                                                                                                        
                                                                    } 
                                                                    $X++; 
                                                                    $ylabel='number of all actions'  ;                                        
                                                            }                                                                        
                                                    } 
                                                    foreach ($all as $o=>$valu){
                                                            if($valu->id==$a && $valu->id==$courseid ){
                                                                    $name=$valu->fullname.' ( '.$valu->idnumber.' )'.'( *** )' ;
                                                            }else if($valu->id==$a){
                                                                    $name=$valu->fullname.' ( '.$valu->idnumber.' )';
                                                            }
                                                    }
                                                    $series = new \core\chart_series($name, $data);
                                                    if($max<=max($data)){
                                                            $max=max($data);
                                                    }                        
                                                    $chart->add_series($series);                      
                                            }                 
                                            $chart->set_labels($labe2);
                                            $yaxis = $chart->get_yaxis(0, true);
                                            $yaxis->set_label($ylabel);
                                            $yaxis->set_stepsize(max(1,round($max  / 10)));                                        
                                            echo $OUTPUT->render($chart);     
                                    } 
                            }                                                                                             
                    }
            }
            elseif($ndays<=0 && $id2>0){
                    echo ' you have only entered user id . so,please select no of days';
            }
            elseif($ndays>0 && $id2<=0){
                    echo 'you have only selected no of days. so,please enter valid user id';
            }
            else{
                    echo 'there is no values to display';
            }
                                                 
        }
        
        function  get_coursees($userid){
            global $DB,$contextids,$instanceids,$i;
            $label=array();
            $i=0;
            // $sql="SELECT id,shortname FROM {role};";
            // $dat=$DB->get_records_sql($sql);
            // foreach($dat as $a=>$val){
            //         echo $val->id.'--'.$val->shortname.'<br>';
            // }
            $sql1 = "SELECT * FROM {role_assignments} WHERE  userid='$userid';";
            $res = $DB->get_records_sql($sql1);
            foreach ($res as $s=>$val){
                    $contextids=$val->contextid;       
                    $sql2 = "SELECT instanceid from {context} WHERE id='$contextids' AND contextlevel=50;";
                    $res1 = $DB->get_records_sql($sql2);
                    foreach($res1 as $d=>$val){
                            $instanceids=$val->instanceid;
                            $sql3 = "SELECT id,shortname,fullname FROM {course} WHERE id='$instanceids';";
                            $res2=$DB->get_records_sql($sql3);
                            foreach($res2 as $f=>$val){
                                    $label[$i]=$val->id;
                                    $i++;
                            }
                    }
            }
            return $label;
    }