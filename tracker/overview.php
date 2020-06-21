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
   $PAGE    ->  navbar->add($title);    //adds title to nav-bar
   //$PAGE    ->  requires->css('/blocks/tracker/styles.css');
  
   require_login($course, true);

   echo $OUTPUT->header();
   //echo $OUTPUT->heading($title, 2);

   echo $OUTPUT->container_start('block_tracker');

    global $DB;

    //AND DATE_FORMAT(FROM_UNIXTIME(timecreated),'%D %M %Y')='$date';";

    $join_scorm_and_scoes = "SELECT id, scorm FROM {scorm_scoes};";
    $joined = $DB->get_records_sql($join_scorm_and_scoes);
    //echo '<pre>'; print_r($joined[1]->scorm); echo '</pre>';    //outputs scorm belonging to id(1)
    //OBJECTID IS TAKEN FROM SCORM_SCOES TABLE. IT NEEDS TO BE CONNECTED WITH SCORM TABLE.

    echo '<head>
        <style>
            table, th, td {
                border: 1px solid black;
                padding: 15px;
            }
            th {
                background-image: linear-gradient(#36d1dc, #AFEEEE);
            }
            td#not-headings {
                color: #808080;
            }
            td#headings {
                background-image: linear-gradient(to right, #36d1dc, #AFEEEE);
            }
        </style>
    </head>';

        echo '<div>
            <table style="width:100%" >
                <tr>
                <th></th>';

            $name = array();
            $x = 0;
            $sco_lessons = "SELECT id, name FROM {scorm} WHERE course = $courseid";
            $info_sco_lessons = $DB->get_records_sql($sco_lessons);

            foreach($info_sco_lessons as $sco_name){
                $name[$x]=$sco_name->name;
                echo '<th>';
                echo $name[$x];
                echo '</th>';
                $x++;
            }

            $id = array();
            $x_id = 0;

            foreach($info_sco_lessons as $sco_id){
                $id[$x_id]=$sco_id->id;
                $x_id++;
            }
            
            //print($name[0]);    //works
                //Lesson 1

            //print($name[$x]); //doesn't work

            //print_r($info_sco_lessons);
                //Array ( [1] => stdClass Object ( [id] => 1 [name] => Lesson 1 ) [2] => stdClass Object ( [id] => 2 [name] => Lesson 2 ) [3] => stdClass Object ( [id] => 3 [name] => lesson3 ) [4] => stdClass Object ( [id] => 4 [name] => Lesson 4 ) [5] => stdClass Object ( [id] => 5 [name] => Lesson 6 ) )

            //echo '<pre>'; print_r($info_sco_lessons[1][name]); echo '</pre>';  //Exception - Cannot use object of type stdClass as array

            //echo '<pre>'; print_r($info_sco_lessons[1]); echo '</pre>';
                //stdClass Object
                    // (
                        //[id] => 1
                        //[name] => Lesson 1
                    // )
                //
            echo '<pre>'; print_r($id); echo '</pre>';
            echo '<pre>'; print_r($name); echo '</pre>';
                //Array
                    // (
                    //     [0] => Lesson 1
                    //     [1] => Lesson 2
                    //     [2] => lesson3
                    //     [3] => Lesson 4
                    //     [4] => Lesson 6
                    // )
                //
            
            //print_r($id);
                //Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 [4] => 5 )

            echo '</tr>';

            $subject=array();
            $stu_name = array();
            $y = 0;

            $stu_id = array();
            $z = 0;

            $users = "SELECT id, username FROM {user}";
            $info_students = $DB->get_records_sql($users);

            $time_created_diff = array();

            $sc=0;
            $sc1=0;

            foreach($info_students as $user_info){
                echo '<tr>';
                    
                    // $stu_id[$z]=$user_info->id;
                    $stu_id[$user_info->id]=$user_info->id;

                    // $stu_name[$y]=$user_info->username;
                    $stu_name[$user_info->id]=$user_info->username;
                    echo '<td id="headings"><b>';
                    echo $user_info->username;
                    echo '</b></td>';
                    $z++;
                    $y++;

                    $sql = "SELECT timecreated, objectid 
                            FROM {logstore_standard_log} 
                            WHERE ((eventname LIKE '%sco_launched' OR eventname LIKE '%content_pages_viewed') 
                                AND userid=$user_info->id) 
                            ORDER BY timecreated DESC;";

                    $time_created_start = array();
                    $time_created_end = array();

                    $result = $DB->get_records_sql($sql);
                    
                    foreach($result as $value){
                        $time_created_start[$sc]=$value->timecreated;
                        $time_created_diff[0][$sc]=$value->timecreated;
                        $time_created_diff[2][$sc]=$value->objectid;
                        $time_created_diff[3][$sc]=$user_info->id;
                        $time_created_diff[4][$sc]=$joined[$value->objectid]->scorm;

                        echo '<td>';

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
                            $time_created_diff[1][$sc1]=($value1->timecreated-$value->timecreated)/60;

                            $sc1++;
                        }
                        $end_time = $value1->timecreated;
                        $start_time = $value->timecreated;
                        $difference = $end_time - $start_time;
                        echo $difference/60;
                        echo " seconds";
                        echo '</td>';
                        $sc++;
                    }
                    // echo '-------------------start start--------------------'.$sc; print($user_info->username);
                    // echo '<pre>'; print_r($time_created_start); echo '</pre>';
                    
                    // echo '-------------------start end--------------------'.$sc1; print($user_info->username);
                    // echo '<pre>'; print_r($time_created_end); echo '</pre>';
                    
                    // echo '-------------------start none--------------------'; print($user_info->username);
                    // echo '<pre>'; print_r($time_created_diff); echo '</pre>';

                    // if (count($result)>0){
                    //     foreach($result as $value){
                    //         $time_created[$sc]=$value->timecreated;
                    //         echo '<td>';

                    //         $sql1 = "SELECT timecreated, eventname, objectid 
                    //                 FROM {logstore_standard_log} 
                    //                 WHERE timecreated>=$value->timecreated 
                    //                     AND userid=$user_info->id 
                    //                     AND (eventname LIKE '%course_viewed' 
                    //                         OR eventname LIKE '%dashboard_viewed' 
                    //                         OR eventname LIKE '%user_loggedout')
                    //                 LIMIT 1;";

                    //             $time_created[$sc1]=$value1->timecreated;
                    //             $sc1++;
                    //             //echo $value1->timecreated. ": ";
                    //         }
                    //         $end_time = $value1->timecreated;
                    //         $start_time = $value->timecreated;
                    //         $difference = $end_time - $start_time;
                    //         echo $difference/60;
                    //         echo " seconds";
                    //         echo '</td>';
                    //         $sc++;
                    //     }
                    // }

                    echo '</tr>';
            }

        // echo '<pre>'; print_r($name); echo '</pre>';
        // echo '<pre>'; print_r($id); echo '</pre>';
        // echo '<pre>'; print_r($stu_name); echo '</pre>';
        // echo '<pre>'; print_r($stu_id); echo '</pre>';
        // echo '<pre>'; print_r($info_students); echo '</pre>';
        // print($sc);
        // print($sc1);
        //echo '<pre>'; print_r($time_created[0]); echo '</pre>';

        /*
        THe following don't return anything.
        $qwerty=$stu_id[0];
        echo '<pre>'; print_r($qwerty); echo '</pre>';        
        
        echo '<pre>'; print_r($stu_name[$stu_id[0]]); echo '</pre>';
        */

        echo '</table>

        </div>';
        echo '<pre>'; print_r($joined); echo '</pre>';
        echo '<pre>'; print_r($time_created_diff[4]); echo '</pre>';
        echo '<pre>'; print_r($time_created_diff[2]); echo '</pre>';
       
   echo $OUTPUT->container_end();

   echo $OUTPUT->container_start('tr');

   echo '<div>';
   $sales = new \core\chart_series('Series 1 (Line)', $name);
   echo '<pre>'; print_r($sales); echo '</pre>';

   $expenses = new \core\chart_series('Series 2 (Line)', $id);
   
   $chart = new \core\chart_line();
   $chart->set_smooth(true); // Calling set_smooth() passing true as parameter, will display smooth lines.
   $chart->add_series($sales);
   $chart->add_series($expenses);
   $labels=array(1, 3, 2, 4, 7);
   $chart->set_labels($labels);
   echo $OUTPUT->render($chart);
   echo '</div>';
   echo $OUTPUT->container_end();


   echo $OUTPUT->footer();

   