<?php
   require_once(dirname(__FILE__) . '/../../config.php');
   require_once($CFG->dirroot.'/blocks/tracker4/lib.php');
   

   define('USER_SMALL_CLASS', 20);   
   define('USER_LARGE_CLASS', 200);  
   define('DEFAULT_PAGE_SIZE', 20);
   define('SHOW_ALL_PAGE_SIZE', 5000);

   $id              = required_param('tracker4id',    PARAM_INT);
   $courseid        = required_param('courseid',   PARAM_INT);
   $userid          = required_param('userid',     PARAM_INT);

   $page            = optional_param('page', 0,    PARAM_INT); 
   $perpage         = optional_param('perpage',    DEFAULT_PAGE_SIZE, PARAM_INT); 
   $group           = optional_param('group', 0,   PARAM_INT); 

   $course          = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
   $context         = block_tracker4_course_context($courseid);

   $loginblock      = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
   $loginsconfig    = unserialize(base64_decode($loginblock->configdata));

   $PAGE->set_course($course);

   $PAGE->set_url(
       '/blocks/tracker4/overview.php',
       array(
           'tracker4id' => $id,
           'courseid'   => $courseid,
           'page'       => $page,
           'perpage'    => $perpage,
           'group'      => $group,
       )
   );

   $PAGE    ->  set_context($context);
   $title = 'Time Spent by Index';
   $PAGE    ->  set_title($title);      //sets title in title-bar
   $PAGE    ->  set_heading($title);    //sets title in header
   $PAGE    ->  navbar->add($title);    //adds title to navbar
  
   require_login($course, true);

   echo $OUTPUT->header();

   echo $OUTPUT->container_start('block_tracker4');

   echo '<div>';

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
                echo html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>'tracker4id', 'value'=>$id));
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

    global $DB;

    //connect scormid and scoid
    $join_scorm_and_scoes = "SELECT id, scorm FROM {scorm_scoes};";
    $joined = $DB->get_records_sql($join_scorm_and_scoes);

    //getting lesson names from db
    $sco_lessons = "SELECT id, name FROM {scorm} WHERE course = $courseid";
    $info_sco_lessons = $DB->get_records_sql($sco_lessons);

    //initialize name list for scorm lessons
    $name = array();
    foreach($info_sco_lessons as $sco_name){
        //entering lesson names into array by id
        array_push($name, $sco_name->name);
    }

    //get name of course
    $fullname = "SELECT fullname FROM {course} WHERE id=$courseid";
    $coursename = $DB->get_records_sql($fullname);
    foreach($coursename as $info_coursename){
        $course_name=$info_coursename->fullname;
    }

    //create a new chart
    $chart = new \core\chart_line();
    //name its axis
    $chart->get_xaxis(0, true)->set_label("Lessons in ". $course_name); 
    $chart->get_yaxis(0, true)->set_label("Time spent per lesson(hrs)");

    //get ids, names of students enrolled in course
    $contextid = get_context_instance(CONTEXT_COURSE, $courseid);
    $users = "SELECT u.id, u.username
                FROM {user} u, {role_assignments} r
                WHERE u.id=r.userid
                    AND r.contextid = {$contextid->id}";
    $info_students = $DB->get_records_sql($users);

    $sc=0;
    $sc1=0;

    $stu_name = array();

    //check if index is valid
    foreach($info_students as $user_info){
        if ($user_info->username==$index){
            $doIExist=1;
        }
    }

    //set heading if index valid
    if ($doIExist==1 && $index!=NULL){
        echo 'Access details of '.$index.': ';
    }
    //make sure index is valid
    else if ($doIExist!=1 && $index!=NULL){
        echo ' Index not valid';
    }

    foreach($info_students as $user_info){
        if ($user_info->username==$index){
            //entering user name into array by id
            $stu_name[$user_info->id]=$user_info->username;

            $access_array=array();

            //find which scorm packages student has accessed
            $sql = "SELECT sst.scormid, sst.scoid, sst.value 
            FROM {scorm_scoes_track} sst, {scorm} s 
            WHERE sst.scormid=s.id 
                AND element='cmi.core.total_time' 
                AND sst.userid=$user_info->id 
                AND s.course=$courseid;";
            $result = $DB->get_records_sql($sql);

            //fill array if student hasn't accessed a scorm pkg
            foreach($info_sco_lessons as $value){
                if (!isset($result[$value->id])){
                    $result[$value->id]->value=0;
                }
            }
            ksort($result); //sort array by key

            $sc=0;

            //expand [value] in $result to convert 00:00:00.00 into hours
            foreach($result as $value){
                $split_time_value = explode (":", $value->value);
                $split_time_value[3]=($split_time_value[0])+($split_time_value[1]/60)+($split_time_value[2]/(60*60));
                if ($split_time_value[3]>0){
                    $access_array[$sc]=$split_time_value[3];
                }
                else{
                    $access_array[$sc]=0;
                }
                $sc++;
            }

            //sets line-chart line to student
            $time_per_student = new core\chart_series($user_info->username, $access_array);
            $chart->add_series($time_per_student);
        } 
    }

    $chart->set_labels($name);

    //renders chart if index valid
    if ($doIExist==1 && $index!=NULL){
        echo $OUTPUT->render($chart);
    }

    echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();

   
   