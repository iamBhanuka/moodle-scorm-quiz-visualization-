<?php

        require_once(dirname(__FILE__) . '/../../config.php');

        // get page context according to id
        function block_myblock_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }

        // selcect category id
        function get_course_data( $id,$type,$uyear,$semester,$ndays,$action){            
                global $DB,$sid,$sc;

                $sc=0;   
                $subject=array();                   
                
                $sql=  "SELECT id FROM {course_categories} WHERE parent
                        IN(SELECT id FROM {course_categories} WHERE parent 
                           IN(SELECT id FROM {course_categories} WHERE  parent 
                              IN(SELECT id FROM {course_categories}  WHERE name='$id')AND name='$type')AND name='$uyear')
                        AND name='$semester';";

                $categorys=$DB->get_records_sql($sql);
                if(count($categorys)>0 && $ndays>0){
                        
                        echo ' '.$id.'  '; echo $type.'  ';echo $uyear.'  '; echo $semester.' course '.$action.' summary in '.$ndays.' days'.'<br>'.'<br>';
                        foreach($categorys as $top=>$value){
                                $sid=$value->id;
                        }
                
                        $sql1="SELECT id FROM {course} WHERE category='$sid';";
                        $course=$DB->get_records_sql($sql1);
                        foreach($course as $sub=>$value){
                                $subject[$sc]=$value->id;
                                $sc++;
                        }                        
                        get_login_data($subject,$ndays,$action); 
                }
               
                else{   if($ndays=='Select no of days'){
                              echo 'please select number of days which you want';
                       
                        } 
                        else{
                                echo  ' In  '.$id. ' there is no values to display'.'<br>'.'<br>';
                        }
                         
                }                  
        };
        
        

        

       