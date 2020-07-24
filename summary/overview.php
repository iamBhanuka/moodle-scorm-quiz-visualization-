<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/blocks/summary/lib.php');
$extention_array = array('jpg','png','jpeg','gif','ico');
define('USER_SMALL_CLASS', 20);   
define('USER_LARGE_CLASS', 200);  
define('DEFAULT_PAGE_SIZE', 20);
define('SHOW_ALL_PAGE_SIZE', 5000);

$id       = required_param('summaryid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$userid   = required_param('userid',PARAM_INT);
$page     = optional_param('page', 0, PARAM_INT); 
$perpage  = optional_param('perpage', DEFAULT_PAGE_SIZE, PARAM_INT); 
$group    = optional_param('group', 0, PARAM_INT); 

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = block_summary_course_context($courseid);

$loginblock = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
$loginsconfig = unserialize(base64_decode($loginblock->configdata));

$PAGE->set_course($course);

$PAGE->set_url(
    '/blocks/summary/overview.php',
    array(
        'summaryid' => $id,
        'courseid' => $courseid,
        'page' => $page,
        'perpage' => $perpage,
        'group' => $group,
    )
);

 $PAGE->set_context($context);
 $title = 'Your Scorm Quiz Marks overview';
 $PAGE->set_title($title);
 $PAGE->set_heading($title);
 $PAGE->navbar->add($title);

require_login($course, false);

echo $OUTPUT->header();
// echo $OUTPUT->heading($title, 2);
echo $OUTPUT->container_start('block_summary');
// $ndayss=array('select number of days','30','60','90','105');
// $actions=array('viewed','All Actions');
     echo html_writer::start_tag('div');
         echo html_writer::start_tag('form', array('action' =>'overview.php', 'method' => 'post'));
            echo html_writer::empty_tag('input', array('type' => 'number', 'name' => 'per1','autocomplete'=>'off','placeholder'=>' enter marks ','style'=>'height:35px ; border:1px solid black')).' ';
            // echo html_writer::select( $ndayss,'per5',$selected5,true).' ';
            // echo html_writer::select( $actions,'per6',$selected6,true).' ';            
             echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'summaryid', 'value' => $id));
             echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
              echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'userid', 'value' => $userid));
              echo'<br>';
              echo'<br>';

             echo html_writer::empty_tag('input', array('type' => 'submit', 'class' => 'btn-primary', 'value' => 'Please Verify Your Marks','style'=>'height:35px ; border:1px solid black'));
         echo html_writer::end_tag('form').'<br>';       
     echo html_writer::end_tag('div');

     echo html_writer::start_tag('div', array('style' => 'border-style:groove ; '));     
         $minmarks= $_POST['per1'];
        //  $ndays=$ndayss[ $_POST['per5'] ];
        //  $action=$actions[ $_POST['per6'] ];      
        //  get_logins_data($id2,$ndays,$action,$courseid);
     echo html_writer::end_tag('div');
    






echo $OUTPUT->container_start('block_summary');
get_summary($courseid,$userid,$minmarks );

echo $OUTPUT->container_end();

echo $OUTPUT->footer();
