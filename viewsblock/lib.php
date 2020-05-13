<?php

        require_once(dirname(__FILE__) . '/../../config.php');

        // get page context according to id
        function block_viewsblock_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }
       
        function  get_coursees($userid,$courseid){
                global $DB,$names,$contexids,$ids,$ids2,$i,$OUTPUT;
                $sql="SELECT id,name FROM {course_sections} WHERE course='$courseid' AND section>0;";
                $data=$DB->get_records_sql($sql);               
                foreach($data as $a=>$value){
                        $label1=array();
                        $label2=array();
                        $i=0;
                        echo html_writer::start_tag('div', array('style' => 'text-align:center ; font-weight:bold ; ')); 
                                echo $value->name.'<br>'; 
                        echo html_writer::end_tag('div');                
                        $sql1="SELECT id,instance,module FROM {course_modules} WHERE course='$courseid' AND section='$value->id';";
                        $data1=$DB->get_records_sql($sql1);
                        foreach($data1 as $a=>$value){
                                $names=$value->instance;
                                $ids=$value->id;                                
                                $sql3="SELECT name FROM {modules} WHERE id='$value->module'";
                                $data3=$DB->get_records_sql($sql3);
                                $sql12 = "SELECT id from {context} WHERE instanceid='$ids' AND  contextlevel=70;";
                                $res1 = $DB->get_records_sql($sql12); 
                                foreach($res1 as $d=>$val){
                                        $ids2=$val->id;
                                }
                                foreach($data3 as $a=>$value){                                        
                                        if($value->name=='assign'){
                                                $sql4="SELECT id,name FROM {assign} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                       $label2[$i]=$value->counts;
                                                }

                                        };
                                        if($value->name=='folder'){
                                                $sql4="SELECT id,name FROM {folder} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;
                                                }
                                        };
                                        if($value->name=='forum'){
                                                $sql4="SELECT id,name FROM {forum} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;
                                                }
                                        };
                                        if($value->name=='scorm'){
                                                $sql4="SELECT id,name FROM {scorm} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;
                                                }
                                        };
                                        if($value->name=='resource'){
                                                $sql4="SELECT id,name FROM {resource} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;
                                                }
                                        };
                                        if($value->name=='quiz'){
                                                $sql4="SELECT id,name FROM {quiz} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };  
                                        if($value->name=='url'){
                                                $sql4="SELECT id,name FROM {url} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='lesson'){
                                                $sql4="SELECT id,name FROM {lesson} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='label'){
                                                $sql4="SELECT id,name FROM {label} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='workshop'){
                                                $sql4="SELECT id,name FROM {workshop} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='wiki'){
                                                $sql4="SELECT id,name FROM {wiki} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='survey'){
                                                $sql4="SELECT id,name FROM {survey} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='page'){
                                                $sql4="SELECT id,name FROM {page} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='lti'){
                                                $sql4="SELECT id,name FROM {lti} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='imscp'){
                                                $sql4="SELECT id,name FROM {imscp} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='glossary'){
                                                $sql4="SELECT id,name FROM {glossary} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='feedback'){
                                                $sql4="SELECT id,name FROM {feedback} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='data'){
                                                $sql4="SELECT id,name FROM {data} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='choice'){
                                                $sql4="SELECT id,name FROM {choice} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='chat'){
                                                $sql4="SELECT id,name FROM {chat} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='book'){
                                                $sql4="SELECT id,name FROM {book} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };
                                        if($value->name=='assignment'){
                                                $sql4="SELECT id,name FROM {assignment} WHERE id='$names' AND course='$courseid';";
                                                $data4=$DB->get_records_sql($sql4);
                                                foreach($data4 as $e=>$value){
                                                        $label1[$i]=$value->name;
                                                }                                               
                                                $sqll="SELECT count(id) AS 'counts' FROM {logstore_standard_log} WHERE action='viewed'  AND  userid='$userid' AND courseid='$courseid' AND contextid='$ids2' AND contextlevel=70;";
                                                $da=$DB->get_records_sql($sqll);
                                                foreach($da as $a=>$value){
                                                        $label2[$i]=$value->counts;                                                   
                                                }
                                        };  
                                        $i++;                                         
                                }
                        }
                        echo html_writer::start_tag('div', array('style' => 'border-style:groove ; height:5% ; '));   
                                if(sizeof($label1)>0){
                                        $chart = new \core\chart_bar(); 
                                        //$chart->set_doughnut(true); 
                                        //$chart->set_legend_options(['position'=>'left']);      
                                        //$chart->set_horizontal(true);
                                        $series1 = new \core\chart_series('views', $label2);
                                        $chart->add_series($series1);       
                                        $chart->set_labels($label1);
                                         $yaxis = $chart->get_yaxis(0, true);
                                         $yaxis->set_label('number of views');
                                        $yaxis->set_stepsize(max(1,round(max($label2)  / 10)));
                                                  
                                        echo $OUTPUT->render($chart);  
                                } 
                        echo html_writer::end_tag('div');                       
                        echo '<br>';
                }                   
        }