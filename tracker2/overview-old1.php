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

    foreach($info_sco_lessons as $sco_name){
        //entering lesson names into array by id
        $name[$sco_name->id]=$sco_name->name;
        array_push($name_array, $sco_name->name);

    }

    //getting user details from db
    $users = "SELECT id, username FROM {user}";
    $info_students = $DB->get_records_sql($users);

    $stu_name = array();

    $time_created_diff = array();
    $sc=0;
    $sc1=0;

$qwerty="SELECT userid, scormid, scoid, value FROM {scorm_scoes_track} WHERE element='cmi.core.total_time';";
$qwerty1=$DB->get_records_sql($qwerty);
echo '<pre>'; print_r($qwerty1); echo '</pre>';
// $q=0;
// foreach($qwerty1 as $sub=>$value){
//     $qwerty2[$q]=$sub;
//     $q++;
// }
// echo '<pre>'; print_r($qwerty2); echo '</pre>';
// // $qwerty2='00:19:25.81';
// $mySplitColumns = explode (":", $qwerty2[$q]);
// $mySplitColumns[3]=$mySplitColumns[0]+($mySplitColumns[1]/24)+($mySplitColumns[2]/(24*60));
// echo '<pre>'; print_r($mySplitColumns); echo '</pre>';

    $chart = new \core\chart_line();
    $chart->set_smooth(true); // Calling set_smooth() passing true as parameter, will display smooth lines.

    foreach($info_students as $user_info){

        //entering user names into array by id
        $stu_name[$user_info->id]=$user_info->username;

        $sql = "SELECT timecreated, objectid 
                FROM {logstore_standard_log} 
                WHERE ((eventname LIKE '%sco_launched' OR eventname LIKE '%content_pages_viewed') 
                    AND userid=$user_info->id) 
                ORDER BY timecreated DESC;";
        $result = $DB->get_records_sql($sql);

        $time_created_start = array();
        $time_created_end = array();
        //$time_created_diff = array();

        $count=array();
                    
        foreach($result as $value){
            $time_created_start[$sc]=$value->timecreated;
            $time_created_diff[user_ids][$sc]=$user_info->id;
            $time_created_diff[scorm_scoes][$sc]=$value->objectid;
            $time_created_diff[scorm][$sc]=$joined[$value->objectid]->scorm;
            $time_created_diff[time_started][$sc]=$value->timecreated;

            $sql1 = "SELECT timecreated, eventname, objectid 
                        FROM {logstore_standard_log} 
                        WHERE timecreated>=$value->timecreated 
                            AND userid=$user_info->id 
                            AND (eventname LIKE '%course_viewed' 
                                OR eventname LIKE '%dashboard_viewed' 
                                OR eventname LIKE '%user_loggedout')
                        LIMIT 1;";
        //check correct course, correct package
            $result1 = $DB->get_records_sql($sql1);

            foreach($result1 as $value1){
                $time_created_end[$sc1]=$value1->timecreated;
                $time_created_diff[time_ended][$sc1]=$value1->timecreated;
                $time_created_diff[time_spent][$sc1]=($value1->timecreated-$value->timecreated)/(60*24);

                $count[$user_info->id][$time_created_diff[scorm][$sc1]][count]++;
                $count[$user_info->id][$time_created_diff[scorm][$sc1]][duration]+=$time_created_diff[time_spent][$sc1];

                $sc1++;
            }
            $sc++;
        }
        //$count[$user_info->id][$c][duration];
        // echo '<pre>'; print_r($count); echo '</pre>';

        $c=1;
        $array=array();
        while($c<=count($name)){
            if ($count[$user_info->id][$c][duration]>0){
                array_push($array, $count[$user_info->id][$c][duration]);
            }
            else { array_push($array, 0); }
            $c++;
        }
        // echo '<pre>'; print_r($array); echo '</pre>';
        
        $lesson = new core\chart_series($user_info->username, $array);
        $chart->add_series($lesson);
    }
    $chart->set_labels($name_array);
    echo $OUTPUT->render($chart);

        echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   