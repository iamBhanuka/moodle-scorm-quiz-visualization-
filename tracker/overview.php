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

    $join_scorm_and_scoes = "SELECT id, scorm FROM {scorm_scoes};";
    $joined = $DB->get_records_sql($join_scorm_and_scoes);

    //$count = array();

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

            $name = array();

            //getting lesson names from db
            $sco_lessons = "SELECT id, name FROM {scorm} WHERE course = $courseid";
            $info_sco_lessons = $DB->get_records_sql($sco_lessons);

            foreach($info_sco_lessons as $sco_name){
                //entering lesson names into array by id
                $name[$sco_name->id]=$sco_name->name;

                //printing column headings into table
                echo '<th>';
                echo $sco_name->name;
                echo '</th>';
            }

            echo '</tr>';

            //getting user details from db
            $users = "SELECT id, username FROM {user}";
            $info_students = $DB->get_records_sql($users);

            $stu_name = array();

            $time_created_diff = array();
            $sc=0;
            $sc1=0;

            foreach($info_students as $user_info){
                echo '<tr>';

                    //entering user names into array by id
                    $stu_name[$user_info->id]=$user_info->username;
                    
                    //printing row headings into table
                    echo '<td id="headings"><b>';
                    echo $user_info->username;
                    echo '</b></td>';

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

                        //echo '<td>';

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
                            $time_created_diff[time_spent][$sc1]=($value1->timecreated-$value->timecreated)/60;

                            $count[$user_info->id][$time_created_diff[scorm][$sc1]][count]++;
                            $count[$user_info->id][$time_created_diff[scorm][$sc1]][duration]+=$time_created_diff[time_spent][$sc1];

                            $sc1++;
                        }
                        $sc++;
                    }
                    //echo '<pre>'; print_r($count); echo '</pre>';


                    $c=1;
                    while($c<=count($name)){
                        echo '<td>';
                        echo "<div id='progressbar'>";
                        if ($count[$user_info->id][$c][count]>0){
                            echo "<div id='completed' style='width: 100% !important;'>";
                            echo '<small>Viewed <b>';
                            echo $count[$user_info->id][$c][count];
                            echo '</b> times</small>';
                        }
                        else { 
                            echo "<div id='not-completed' style='width: 100% !important;'>";
                            echo '<small>Not Viewed</small>';
                            //echo 0;
                        }
                        echo "</div>";
                        echo "</div>";
                        echo '</td>';
                        // echo '<td>Viewed ';
                        // if ($count[$user_info->id][$c][count]>0){
                        //     echo $count[$user_info->id][$c][count];
                        // }
                        // else {  echo 0; }
                        // echo ' times</td>';

                        $c++;
                    }
                    echo '</tr>';
            }
        echo '</table>

        </div>';

        // echo 'joined' ;
        // echo '<pre>'; print_r($joined); echo '</pre>';
        // echo 'count' ;
        // echo '<pre>'; print_r($count); echo '</pre>';
        // echo 'name' ;
        // echo '<pre>'; print_r($name); echo '</pre>';
        // echo 'stu_name' ;
        // echo '<pre>'; print_r($stu_name); echo '</pre>';
        // echo 'time_created_diff' ;
        // echo '<pre>'; print_r($time_created_diff); echo '</pre>';
        // echo 'time_created_start' ;
        // echo '<pre>'; print_r($time_created_start); echo '</pre>';
        // echo 'time_created_end' ;
        // echo '<pre>'; print_r($time_created_end); echo '</pre>';

    // $example=array();
    // $example[0][0][0]=0;
    // $example[0][0][1]=1;
    // $example[0][0][2]=2;
    // $example[0][1][0]=3;
    // $example[0][1][1]=4;
    // $example[0][1][2]=5;
    // $example[0][2][0]=6;
    // $example[0][2][1]=7;
    // $example[1][0][0]=8;
    // $example[2][0][0]=9;
    // echo '<pre>'; print_r($example); echo '</pre>';

       
   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   