<?php
   require_once(dirname(__FILE__) . '/../../config.php');
   require_once($CFG->dirroot.'/blocks/tracker2/lib.php');
   

   define('USER_SMALL_CLASS', 20);   
   define('USER_LARGE_CLASS', 200);  
   define('DEFAULT_PAGE_SIZE', 20);
   define('SHOW_ALL_PAGE_SIZE', 5000);

   $id              = required_param('logingraphid',    PARAM_INT);
   $courseid        = required_param('courseid',   PARAM_INT);
   $userid          = required_param('userid',     PARAM_INT);

   $page            = optional_param('page', 0,    PARAM_INT); 
   $perpage         = optional_param('perpage',    DEFAULT_PAGE_SIZE, PARAM_INT); 
   $group           = optional_param('group', 0,   PARAM_INT); 

   $course          = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
   $context         = block_tracker2_course_context($courseid);

   $loginblock      = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
   $loginsconfig    = unserialize(base64_decode($loginblock->configdata));

   $PAGE->set_course($course);

   $PAGE->set_url(
       '/blocks/tracker2/overview.php',
       array(
           'logingraphid'    => $id,
           'courseid'   => $courseid,
           'page'       => $page,
           'perpage'    => $perpage,
           'group'      => $group,
       )
   );

   $PAGE    ->  set_context($context);
   $title = 'Time Spent per Lesson';
   $PAGE    ->  set_title($title);      //sets title in title-bar
   $PAGE    ->  set_heading($title);    //sets title in header
   $PAGE    ->  navbar->add($title);    //adds title to navbar
  
   require_login($course, true);

   echo $OUTPUT->header();

   echo $OUTPUT->container_start('block_tracker2');
   echo '<div>';

    global $DB;

    $join_scorm_and_scoes = "SELECT id, scorm FROM {scorm_scoes};";
    $joined = $DB->get_records_sql($join_scorm_and_scoes);

    $name = array();
    $name_array = array();

    //getting lesson names from db
    $sco_lessons = "SELECT id, name FROM {scorm} WHERE course = $courseid";
    $info_sco_lessons = $DB->get_records_sql($sco_lessons);

    $array=array();

    foreach($info_sco_lessons as $sco_name){
        //entering lesson names into array by id
        $name[$sco_name->id]=$sco_name->name;
        array_push($name_array, $sco_name->name);
    }

    //getting user details from db
    $users = "SELECT id, username FROM {user}";
    $info_students = $DB->get_records_sql($users);

    $stu_name = array();

    // $time_created_diff = array();
    $sc=0;
    $sc1=0;

    $chart = new \core\chart_line();
    //$chart->set_smooth(true); // Calling set_smooth() passing true as parameter, will display smooth lines.

    foreach($info_students as $user_info){

        //entering user names into array by id
        $stu_name[$user_info->id]=$user_info->username;

        $sql="SELECT scormid, scoid, value 
        FROM {scorm_scoes_track} 
        WHERE element='cmi.core.total_time'
            AND userid=$user_info->id;";
        $result = $DB->get_records_sql($sql);
        // echo '<pre>'; print_r($result); echo '</pre>';

        $sc=0;
        $array=array();
        $sco_lessons = "SELECT id FROM {scorm} WHERE course = $courseid";
        $info_sco_lessons = $DB->get_records_sql($sco_lessons);
    
        foreach($info_sco_lessons as $sco_id){
            array_push($array, 0);
        }

        foreach($result as $value){
            // $time_created_diff[$value->scormid][value]=$value->value;
            $mySplitColumns = explode (":", $value->value);
            // $mySplitColumns[3]=($mySplitColumns[0]*60)+($mySplitColumns[1])+($mySplitColumns[2]/60);
            $mySplitColumns[3]=($mySplitColumns[0])+($mySplitColumns[1]/60)+($mySplitColumns[2]/(60*60));
            $time_created_diff[$value->scormid][timespent]=$mySplitColumns[3];
            if ($mySplitColumns[3]>0){
                $array[($value->scormid)-1]=$mySplitColumns[3];
            }
            $sc++;
        }
        // echo '<pre>'; print_r($time_created_diff); echo '</pre>';
        // $time_created_diff=array();
        // echo '<pre>'; print_r($array); echo '</pre>';

        $lesson = new core\chart_series($user_info->username, $array);
        $chart->add_series($lesson);

        $sco_lessons = "SELECT id FROM {scorm} WHERE course = $courseid";
        $info_sco_lessons = $DB->get_records_sql($sco_lessons);
    
        $sc1=0;
        foreach($info_sco_lessons as $sco_id){
            $array[$sc1]=0;
        }
    }

    $chart->set_labels($name_array);
    echo $OUTPUT->render($chart);

    echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   