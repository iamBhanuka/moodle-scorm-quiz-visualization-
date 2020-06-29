<?php
   require_once(dirname(__FILE__) . '/../../config.php');
   require_once($CFG->dirroot.'/blocks/tracker/lib.php');
   

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
   $context         = block_tracker_course_context($courseid);

   $loginblock      = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
   $loginsconfig    = unserialize(base64_decode($loginblock->configdata));

   $PAGE->set_course($course);

   $PAGE->set_url(
       '/blocks/tracker/overview.php',
       array(
           'logingraphid'    => $id,
           'courseid'   => $courseid,
           'page'       => $page,
           'perpage'    => $perpage,
           'group'      => $group,
       )
   );

   $PAGE    ->  set_context($context);
   $title = 'Lecture Access Frequency';
   $PAGE    ->  set_title($title);      //sets title in title-bar
   $PAGE    ->  set_heading($title);    //sets title in header
   $PAGE    ->  navbar->add($title);    //adds title to navbar
  
   require_login($course, true);

   echo $OUTPUT->header();

   echo $OUTPUT->container_start('block_tracker');

   global $DB;

   //connect scormid and scoid
   $join_scorm_and_scoes = "SELECT id, scorm FROM {scorm_scoes};";
   $joined = $DB->get_records_sql($join_scorm_and_scoes);

   $info_sco_lessons=array();

   //getting lesson names from db
   $sco_lessons = "SELECT id, name FROM {scorm} WHERE course = $courseid";
   $info_sco_lessons = $DB->get_records_sql($sco_lessons);

if (empty($info_sco_lessons)){ echo "No scorm packages for this course.";    }

else {
    echo '<head>
        <style>
            table, th, td {
                border: 5px solid white;
                padding: 2px;
            }
            th {
                background-image: linear-gradient(to right, #36d1dc, #AFEEEE);Z
            }
            td#not-headings {
                color: #808080;
            }
            td#headings {
                height: 25px;
                background-image: linear-gradient(#36d1dc, #AFEEEE);
            }
            #progressbar {
                width: 100px;
                height: 20px;
                border-radius: 1px;
                overflow: hidden;
            }
            #completed {
                text-align: center;
                position: relative;
                height: 100%;
                background-image: linear-gradient(to right, #2AF598, #00FF00);
            }
            #not-completed {
                text-align: center;
                position: relative;
                height: 100%;
                background-image: linear-gradient(to right, #FF7700, #FFFF00);
            }
        </style>
    </head>';

        echo '<div>
            <table>
                <tr>
                <th></th>';

            //initialize name list for scorm lessons
            $name = array();
            foreach($info_sco_lessons as $sco_name){
                //entering lesson names into array by id
                $name[$sco_name->id]=$sco_name->name;

                //printing column headings into table
                echo '<th>';
                echo $sco_name->name;
                echo '</th>';
            }

            echo '</tr>';

            //get ids, names of students enrolled in course
            $contextid = get_context_instance(CONTEXT_COURSE, $courseid);
            $users = "SELECT u.id, u.username
                        FROM {user} u, {role_assignments} r
                        WHERE u.id=r.userid
                            AND r.contextid = {$contextid->id}
                        ORDER BY u.username";
            $info_students = $DB->get_records_sql($users);
            
            $stu_name = array();

            foreach($info_students as $user_info){
                echo '<tr>';

                    //entering user names into array by id
                    $stu_name[$user_info->id]=$user_info->username;
                    
                    //printing row headings into table
                    echo '<td id="headings"><b>';
                    echo $user_info->username;
                    echo '</b></td>';

                    //getting access details of the student with $user_info->id
                    $sql = "SELECT lsl.timecreated, lsl.objectid, lsl.userid, lsl.courseid, ss.scorm 
                            FROM {logstore_standard_log} lsl, {scorm_scoes} ss
                            WHERE ( lsl.objectid=ss.id 
                                AND (eventname LIKE '%sco_launched' OR eventname LIKE '%content_pages_viewed') 
                                AND userid=$user_info->id
                                AND courseid=$courseid) 
                            ORDER BY timecreated DESC;";
                    $result = $DB->get_records_sql($sql);

                    //creating an array to keep of the number of times the above student accesses a pkg
                    $count=array();
                    foreach($result as $value){
                        $count[$value->scorm]++;
                    }
                    //filling array for pkgs student hasn't accessed
                    foreach($info_sco_lessons as $value){
                        if (!isset($count[$value->id])){
                            $count[$value->id]=0;
                        }
                    }
                    ksort($count);  //sorting array

                    //entering count per lesson into table
                    foreach($name as $key=>$value){
                        echo '<td>';
                            echo "<div id='progressbar'>";
                                if ($count[$key]>0){    //if pkg has been accessed at least once
                                    echo "<div id='completed' style='width: 100% !important;'>";
                                    echo '<small>Viewed <b>';
                                    echo $count[$key];
                                    echo '</b> times</small>';
                                }
                                else {                  //if pkg hasn't been accessed
                                    echo "<div id='not-completed' style='width: 100% !important;'>";
                                    echo '<small>Not Viewed</small>';
                                    //echo 0;
                                }
                                echo "</div>";
                            echo "</div>";
                        echo '</td>';
                    }
                echo '</tr>';
            }
        echo '</table>';
}
        echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   