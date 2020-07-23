<?php
   require_once(dirname(__FILE__) . '/../../config.php');
   require_once($CFG->dirroot.'/blocks/tracker3/lib.php');
   

   define('USER_SMALL_CLASS', 20);   
   define('USER_LARGE_CLASS', 200);  
   define('DEFAULT_PAGE_SIZE', 20);
   define('SHOW_ALL_PAGE_SIZE', 5000);

   $id              = required_param('tracker3id',    PARAM_INT);
   $courseid        = required_param('courseid',   PARAM_INT);
   $userid          = required_param('userid',     PARAM_INT);

   $page            = optional_param('page', 0,    PARAM_INT); 
   $perpage         = optional_param('perpage',    DEFAULT_PAGE_SIZE, PARAM_INT); 
   $group           = optional_param('group', 0,   PARAM_INT); 

   $course          = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
   $context         = block_tracker3_course_context($courseid);

   $loginblock      = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
   $loginsconfig    = unserialize(base64_decode($loginblock->configdata));

   $PAGE->set_course($course);

   $PAGE->set_url(
       '/blocks/tracker3/overview.php',
       array(
           'tracker3id'    => $id,
           'courseid'   => $courseid,
           'page'       => $page,
           'perpage'    => $perpage,
           'group'      => $group,
       )
   );

   $PAGE    ->  set_context($context);
   $title = 'Time Spent per Day';
   $PAGE    ->  set_title($title);      //sets title in title-bar
   $PAGE    ->  set_heading($title);    //sets title in header
   $PAGE    ->  navbar->add($title);    //adds title to navbar
  
   require_login($course, true);

   echo $OUTPUT->header();

   echo $OUTPUT->container_start('block_tracker3');
   echo '<div>';

   
   global $DB;
    
    //connect scormid and scoid
    // $sql = "SELECT  `mdl_scorm_scoes`.`id` as ss_id, `mdl_scorm`.`id` as s_id, `mdl_scorm_scoes`.`scorm`, `mdl_scorm`.`name` FROM `mdl_scorm`, `mdl_scorm_scoes` WHERE `mdl_scorm`.`id`=`mdl_scorm_scoes`.`scorm` AND `mdl_scorm`.`course`=16";
    $join_scorm_and_scoes = "SELECT  ss.id as sco, ss.scorm, s.name 
                            FROM {scorm} as s, {scorm_scoes} as ss 
                            WHERE s.id=ss.scorm 
                                    AND s.course=$courseid;";
    $joined = $DB->get_records_sql($join_scorm_and_scoes);
    //echo '<pre>'; print_r($joined); echo '</pre>';

if (empty($joined)){ echo "No scorm packages for this course.";    }

else {
    $get_timecreated = "SELECT timecreated, objectid, userid  
                        FROM {logstore_standard_log} 
                        WHERE eventname LIKE '%sco_launched' 
                            AND courseid=$courseid;";
    $timestart = $DB->get_records_sql($get_timecreated);
    //echo '<pre>'; print_r($timestart); echo '</pre>';

    $array=array();
    $c=0;
    foreach ($timestart as $value){
        $array[$c][sco]=$joined[$value->objectid]->name;
        $array[$c][stu_id]=$value->userid;
        $array[$c][date]=date('m/d/Y', $value->timecreated);
        $array[$c][time]=date('h:m:s', $value->timecreated);
        $array[$c][timestart]=$value->timecreated;
        //$array=date('m/d/Y', $value->timecreated)]=date('h:m:s', $value->timecreated);
        //array_push($array, date('m/d/Y h:m:s', $value->timecreated));

        $get_timestopped = "SELECT timecreated 
                            FROM {logstore_standard_log} 
                            WHERE timecreated>=$value->timecreated 
                                AND (eventname LIKE '%course_viewed' 
                                    OR `eventname` LIKE '%dashboard_viewed')
                                AND courseid=$courseid
                                AND userid=$value->userid 
                            ORDER BY `timecreated` DESC 
                            LIMIT 1;";
        $timestop = $DB->get_records_sql($get_timestopped);
        //echo '<pre>'; print_r($timestop); echo '</pre>';

        foreach($timestop as $value){
            $array[$c][timestop]=$value->timecreated;
        }
        if (!isset($array[$c][timestop])){
            $array[$c][timestop]=$array[$c][timestart];
        }
        // foreach($info_sco_lessons as $value){
        //     if (!isset($result[$value->id])){
        //         $result[$value->id]->value=0;
        //     }
        // }
        $array[$c][time_diff]=($array[$c][timestop]-$array[$c][timestart])/(60*60);
        $c++;

    }

    echo '<pre>'; print_r($array); echo '</pre>';

    // echo date('m/d/Y', 1590299351);
    // echo 1590299351;
    // echo strtotime("05/24/2020");
}

   
   echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();
