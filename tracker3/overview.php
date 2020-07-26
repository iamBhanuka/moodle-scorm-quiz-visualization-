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
    
   $twoMonthAgo = date("d-m-Y", strtotime("-2 months"));
   $today = date("d-m-Y");

   //connect scormid and scoid
   // $sql = "SELECT  `mdl_scorm_scoes`.`id` as ss_id, `mdl_scorm`.`id` as s_id, `mdl_scorm_scoes`.`scorm`, `mdl_scorm`.`name` FROM `mdl_scorm`, `mdl_scorm_scoes` WHERE `mdl_scorm`.`id`=`mdl_scorm_scoes`.`scorm` AND `mdl_scorm`.`course`=16";
   $join_scorm_and_scoes = "SELECT  ss.id as sco_id, ss.scorm, s.name as sco_name
                           FROM {scorm} as s, {scorm_scoes} as ss 
                           WHERE s.id=ss.scorm 
                                   AND s.course=$courseid;";
   $joined = $DB->get_records_sql($join_scorm_and_scoes);
   //echo '<pre>'; print_r($joined); echo '</pre>';

if (empty($joined)){ echo "No scorm packages for this course.";    }

else {

    //is index valid
    $doIExist=0;
    //Getting the index
    $index=0;
    echo '<head>
        <style>
            input:focus{    outline: 2px solid purple;    }
        </style>
    </head>';

    //start a form to get index from user
    echo html_writer::start_tag('form', array('action' =>'overview.php', 'method' => 'post'));

    //use table for neater formatting
    echo '<table>';
        echo '<tr>';
            //get index as input
            echo '<td>';
                echo html_writer::empty_tag('input', array('type'=>'text', 'name'=>'id', 'autocomplete'=>'off', 'placeholder'=>' Enter student id ', 'style'=>'height:35px; width:150px; border:1px solid purple'));
            echo '</td>';

            //other prefixed inputs
                echo html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'tracker3id', 'value'=>$id));
                echo html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'courseid', 'value'=>$courseid));
                echo html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'userid', 'value'=>$userid));
            
            //submit all inputs
            echo '<td>';          
                echo html_writer::empty_tag('input', array('type'=>'submit', 'class'=>'btn-primary', 'value'=> 'scorm access details', 'style'=>'height:35px; width:150px; border:1px; background-color:purple'));
            echo '</td>';
        echo '</tr>';
    echo '</table>'; 

    echo html_writer::end_tag('form').'<br>';

    //get input to use in chart
    $index= $_POST['id'];

    function displayDates($date1, $date2, $format = 'd-m-Y' ) {
        $dates = array();
        $array_dates=array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
            array_push($dates, date($format, $current));
            $current = strtotime($stepVal, $current);
            //echo "date ------------".strtotime(date($format, $current));
        }
        return $dates;
    }
    $date_find=displayDates($twoMonthAgo, $today);
    //echo '<pre>'; print_r($date_find); echo '</pre>';

    //create a new chart
    $chart = new \core\chart_line();
    //name its axis
    $chart->get_xaxis(0, true)->set_label("Days"); 
    $chart->get_yaxis(0, true)->set_label("Time spent per lesson(hrs)");

    //get ids, names of students enrolled in course
    $contextid = get_context_instance(CONTEXT_COURSE, $courseid);
    $users = "SELECT u.id, u.username
                FROM {user} u, {role_assignments} r
                WHERE u.id=r.userid
                    AND r.contextid = {$contextid->id}
                ORDER BY u.username";
    $info_students = $DB->get_records_sql($users);
    //echo '<pre>'; print_r($info_students); echo '</pre>';
    //echo '<pre>'; print_r($info_students[2]->username); echo '</pre>';

    $stu_name = array();

    //check if index is valid
    foreach($info_students as $user_info){
        if ($user_info->username==$index){
            $doIExist=1;
            $index_id = $user_info->id;
        }
    }
    //echo "id".$index_id;

    //set heading if index valid
    if ($doIExist==1 && $index!=NULL){
        echo 'Access details of '.$index.': ';
    }
    //make sure index is valid
    else if ($doIExist!=1 && $index!=NULL){
        echo ' Index not valid';
    }

if ($doIExist==1 && $index!=NULL){
    $get_timecreated = "SELECT timecreated, objectid, userid  
                        FROM {logstore_standard_log} 
                        WHERE eventname LIKE '%sco_launched' 
                            AND courseid=$courseid
                            AND userid=$index_id;";
    $timestart = $DB->get_records_sql($get_timecreated);
    // echo '<pre>'; print_r($timestart); echo '</pre>';

    $array=array();
    $c=0;
    foreach ($timestart as $value){
        $array[$c][sco_name]=$joined[$value->objectid]->sco_name;
        $array[$c][stu_id]=$value->userid;
        $array[$c][date]=date('d-m-Y', $value->timecreated);
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
                            ORDER BY `timecreated` ASC
                            LIMIT 1;";
        $timestop = $DB->get_records_sql($get_timestopped);
        //echo '<pre>'; print_r($timestop); echo '</pre>';

        foreach($timestop as $value){
            $array[$c][timestop]=$value->timecreated;
        }
        if (!isset($array[$c][timestop])){
            $array[$c][timestop]=$array[$c][timestart];
        }
        $array[$c][time_diff]=($array[$c][timestop]-$array[$c][timestart])/(60*60);
        if ($array[$c][time_diff]>=4){
            $array[$c][time_diff]=4;
        }
        $c++;
    }
    // echo "c".$c;
    // echo '<pre>'; print_r($array); echo '</pre>';

    function makeDateArray($date1, $date2, $format = 'd-m-Y' ) {
        $array_dates=array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
            $array_dates[date($format, $current)] = 0;
            $current = strtotime($stepVal, $current);
        }
        return $array_dates;
    }
    $date_get=makeDateArray($twoMonthAgo, $today);
    //echo '<pre>'; print_r($date_get); echo '</pre>';

    $c2=0;
    $c3=0;
    $array2=array();
    foreach($joined as $sco_value){
        if ($sco_value->sco_id%2==0){
            //echo $sco_value->sco_name.'<br>';
            for($c2 = 0; $c2 <= $c; $c2++){
                if ($array[$c2][sco_name]==$sco_value->sco_name){
                    // echo $twoMonthAgo.'<br>';
                    // echo $today.'<br>';
                    //if (strtotime($array[$c2][date])<=strtotime($today)){
                    if (strtotime($array[$c2][date])>=strtotime($twoMonthAgo)){
                        // echo '<br>'.strtotime($twoMonthAgo);
                        // echo '<br>'.$array[$c2][date].'<br>';
                    // }
                        //$date_get[$array[$c2][date]]++;
                        $date_get[$array[$c2][date]]+=$array[$c2][time_diff];
                    }
                }
            }
            //echo '<pre>'; print_r($date_get); echo '</pre>';
            $c4=0;
            $array_final=array();
            foreach($date_get as $key=>$value){
                $array_final[$c4]=$value;
                $c4++;
            }
            //echo '<pre>'; print_r($array_final); echo '</pre>';

            //echo '<pre>'; print_r($date_get); echo '</pre>';
            //$time=array(2, 4, 10, 3, 2, 3, 4, 3, 4, 3, 4, 5, 9, 5, 6, 2, 23, 4, 3, 23, 1, 4, 3, 5, 3, 5, 6, 11, 2, 3, 21, 2, 4, 10, 3, 2, 3, 4, 3, 4, 3, 4, 5, 9, 5, 6, 2, 23, 4, 3, 23, 1, 4, 3, 5, 3, 5, 6, 11, 2, 3, 21);
            $time_per_student = new core\chart_series($sco_value->sco_name, $time);
            $time_per_student = new core\chart_series($sco_value->sco_name, $array_final);
            $chart->add_series($time_per_student);
            $date_get=makeDateArray($twoMonthAgo, $today);
        }
    }


    // $date=displayDates("16-6-2020", "27-6-2020");
    $chart->set_labels($date_find);

}
    //renders chart if index valid
    if ($doIExist==1 && $index!=NULL){
        echo $OUTPUT->render($chart);
    }
}   
   echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();
