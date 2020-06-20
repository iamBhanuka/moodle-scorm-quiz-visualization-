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
//   $sql="SELECT timecreated FROM {logstore_standard_log} WHERE (eventname LIKE '%sco_launched' OR eventname LIKE '%content_page_viewed') ORDER BY timecreated DESC;";
//   $result=$DB->get_records_sql($sql);
//   $sc=0;   
//   $stu_name=array();
//   echo '<table>';
//     if (count($result)>0){
//         foreach($result as $sub=>$value){
//             echo '<tr>';

//             $stu_name[$sc]=$value->timecreated;
//             echo '<td>';
//             echo $value->timecreated. ":    ";
//             echo '</td>';
            
//             $sql1="SELECT timecreated, eventname FROM {logstore_standard_log} WHERE timecreated>$value->timecreated AND (eventname LIKE '%course_viewed' OR eventname LIKE '%dashboard_viewed') LIMIT 1;";
//             $result1=$DB->get_records_sql($sql1);
//             $sc1=0;
//             foreach($result1 as $sub=>$value1){
//                 $stu_name[$sc1]=$value1->timecreated;
//                 echo '<td>';
//                 echo $value1->timecreated. ": ";
//                 echo '</td>';

//             }
//             $end_time = $value1->timecreated;
//             $start_time = $value->timecreated;
//             $difference = $end_time - $start_time;
//             echo '<td>';
//             echo $difference/60;
//             echo " seconds";
//             echo '</td>';
//             $sc++;

//             echo '</tr>';
//         }
//     }
//     echo '</table>';

//   $conn->close();


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
            
            //echo '<pre>'; print_r($name); echo '</pre>';
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
                

                    // $sql="SELECT * 
                    //         FROM {logstore_standard_log} 
                    //         LEFT JOIN {scorm} 
                    //         ON {logstore_standard_log}.objectid = {scorm}.id 
                    //         WHERE eventname LIKE '%sco_launched' 
                    //             AND userid=$user_info->id 
                    //         UNION 
                    //         SELECT * 
                    //         FROM {logstore_standard_log} 
                    //         RIGHT JOIN {scorm} 
                    //         ON {logstore_standard_log}.objectid = {scorm}.id 
                    //         WHERE eventname LIKE '%sco_launched'
                    //             AND userid=$user_info->id;";

                    $sql = "SELECT timecreated 
                            FROM {logstore_standard_log} 
                            WHERE ((eventname LIKE '%sco_launched' OR eventname LIKE '%content_pages_viewed') 
                                AND userid=$user_info->id) 
                            ORDER BY timecreated DESC;";

                    $time_created = array();    //Overwritten repeatedly. Only 1 value present in array at any moment.
                    $result = $DB->get_records_sql($sql);
                    // echo '<pre>'; print_r($result); echo '</pre>';

                    $sc=0;   
                    if (count($result)>0){
                        foreach($result as $value){
                            $time_created[$sc]=$value->timecreated;
                            echo '<td>';

                            $sql1 = "SELECT timecreated, eventname, objectid 
                                    FROM {logstore_standard_log} 
                                    WHERE timecreated>=$value->timecreated 
                                        AND userid=$user_info->id 
                                        AND (eventname LIKE '%course_viewed' 
                                            OR eventname LIKE '%dashboard_viewed' 
                                            OR eventname LIKE '%user_loggedout')
                                    LIMIT 1;";

                            $result1=$DB->get_records_sql($sql1);
                            $sc1=0;
                            foreach($result1 as $sub=>$value1){
                                $time_created[$sc1]=$value1->timecreated;
                                $sc1++;
                                //echo $value1->timecreated. ": ";
                            }
                            $end_time = $value1->timecreated;
                            $start_time = $value->timecreated;
                            $difference = $end_time - $start_time;
                            echo $difference/60;
                            echo " seconds";
                            echo '</td>';
                            $sc++;
                        }
                    }

                    // echo '<td id="not-headings">';

                    // echo '<br/>';

                    // echo '</td>';

                    echo '</tr>';


            }

                // while($y <= 8) {
                //     echo '<tr>
                //         <td id="headings"><b>Student name</b></td>
                //     ';

        //$z = 1;    
                //         while($z <= count($info_sco_lessons)) {
                //                 echo '<td id="not-headings">Accessed _ times<br>Spent _ hrs</td>';
                //             $z++;
                //         }

                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //         // <td id="not-headings">Accessed _ times<br>Spent _ hrs</td>
                //     echo '</tr>';
                //     $y++;
                // }

        echo '<pre>'; print_r($name); echo '</pre>';
        echo '<pre>'; print_r($id); echo '</pre>';
        echo '<pre>'; print_r($stu_name); echo '</pre>';
        echo '<pre>'; print_r($stu_id); echo '</pre>';
        echo '<pre>'; print_r($info_students); echo '</pre>';
        print($sc);
        print($sc1);
        //echo '<pre>'; print_r($time_created[0]); echo '</pre>';

        /*
        THe following don't return anything.
        $qwerty=$stu_id[0];
        echo '<pre>'; print_r($qwerty); echo '</pre>';        
        
        echo '<pre>'; print_r($stu_name[$stu_id[0]]); echo '</pre>';
        */

        echo '</table>

        </div>';

    //    echo'<div>';
    //        echo'<form action="overview.php" method="POST">';
    //            echo'<select name="per1" >';       
    //                echo'<option >';echo'Academic year 2020';echo'</option>';
    //                echo'<option >';echo'Academic year 2019';echo'</option>';
    //                echo'<option >';echo'Academic year 2018';echo'</option>';            
    //            echo'</select>'.' ';
    //            echo'<select name="per2" >';        
    //                echo'<option >';echo'SCS';echo'</option>';
    //                echo'<option >';echo'IS';echo'</option>';     
    //            echo'</select>'.' ';
    //            echo'<select name="per3" > ';       
    //                echo'<option >';echo'1 st Year';echo'</option>';
    //                echo'<option >';echo'2 nd Year';echo'</option>';
    //                echo'<option >';echo'3 rd Year';echo'</option>';
    //                echo'<option >';echo'4 th Year';echo'</option>';     
    //            echo'</select>'.' ';
    //            echo'<select name="per4" >';        
    //                echo'<option >';echo'1 st Semester';echo'</option>';
    //                echo'<option >';echo'2 nd Semester';echo'</option>';
    //            echo'</select>'.' ';
    //            echo'<select name="per5" >';
    //                echo'<option >';echo'30';echo'</option>';
    //                echo'<option >';echo'60';echo'</option>';
    //                echo'<option >';echo'120';echo'</option>';
    //                echo'<option >';echo'150';echo'</option>';       
    //            echo'</select>'.' ';
    //            echo'<select name="per6" >';
    //                echo'<option >';echo'viewed';echo'</option>';
    //                echo'<option >';echo'All Actions';echo'</option>';  
    //            echo'</select>'.' ';
    //            echo'<select name="logingraphid" hidden>';
    //                echo'<option >';echo $id;echo'</option>';
    //            echo'</select>'.' ';
    //            echo'<select name="courseid" hidden>';
    //                echo'<option >';echo $courseid ;echo'</option>'; 
    //            echo'</select>'.' ';
    //            echo'<select name="userid" hidden>';
    //                echo'<option >';echo $userid;echo'</option>'; 
    //            echo'</select>'.' ';            

    //            echo'<input class="btn-primary" type="submit" value=" summary ">';
    //        echo'</form>';
    //    echo'</div>';
    //    echo'<div>';
    //        $id2=$_POST['per1'];
    //        $type=$_POST['per2'];
    //        $uyear=$_POST['per3'];
    //        $semester=$_POST['per4'];
    //        $ndays=$_POST['per5'];
    //        $action=$_POST['per6'];
           
    //        get_course_data($id2,$type,$uyear,$semester,$ndays,$action) ;
    //    echo '</div>';
       
   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   