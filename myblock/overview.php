<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    require_once($CFG->dirroot.'/blocks/myblock/lib.php');
    

    define('USER_SMALL_CLASS', 20);   
    define('USER_LARGE_CLASS', 200);  
    define('DEFAULT_PAGE_SIZE', 20);
    define('SHOW_ALL_PAGE_SIZE', 5000);

    $id       = required_param('logingraphid', PARAM_INT);
    $courseid = required_param('courseid', PARAM_INT);
    $userid   = required_param('userid',PARAM_INT);
    $page     = optional_param('page', 0, PARAM_INT); 
    $perpage  = optional_param('perpage', DEFAULT_PAGE_SIZE, PARAM_INT); 
    $group    = optional_param('group', 0, PARAM_INT); 

    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
    $context = block_myblock_course_context($courseid);

    $loginblock = $DB->get_record('block_instances', array('id' => $id), '*', MUST_EXIST);
    $loginsconfig = unserialize(base64_decode($loginblock->configdata));

    $PAGE->set_course($course);

    $PAGE->set_url(
        '/blocks/myblock/overview.php',
        array(
            'logingraphid' => $id,
            'courseid' => $courseid,
            'page' => $page,
            'perpage' => $perpage,
            'group' => $group,
        )
    );

    $PAGE->set_context($context);
    $title = 'Overview of students';
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->navbar->add($title);
  
   
    require_login($course, false);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($title, 2);

    echo $OUTPUT->container_start('block_myblock');

        $acdemicyear=array('Academic year 2020','Academic year 2019','Academic year 2018');
        $types=array('SCS','IS');
        $years=array('1 st Year','2 nd Year','3 rd Year','4 th Year');
        $semesters=array('1 st Semester','2 nd Semester');
        $ndayss=array('30','60','90','105');
        $actions=array('viewed','All Actions');

        echo html_writer::start_tag('div');
            echo html_writer::start_tag('form', array('action' =>'overview.php', 'method' => 'post'));
                echo html_writer::select( $acdemicyear,'per1',$selected1,true).' ';
                echo html_writer::select( $types,'per2',$selected2,true).' ';
                echo html_writer::select( $years,'per3',$selected3,true).' ';
                echo html_writer::select( $semesters,'per4',$selected4,true).' ';
                echo html_writer::select( $ndayss,'per5',$selected5,true).' ';
                echo html_writer::select( $actions,'per6',$selected6,true).' ';            
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'logingraphid', 'value' => $id));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'courseid', 'value' => $courseid));
                echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'userid', 'value' => $userid));

                echo html_writer::empty_tag('input', array('type' => 'submit', 'class' => 'btn-primary', 'value' => 'courses summary','style'=>'height:35px ; border:1px solid black'));
            echo html_writer::end_tag('form').'<br>';       
        echo html_writer::end_tag('div');

        echo html_writer::start_tag('div', array('style' => 'border-style:groove ; '));     
            $id2=$acdemicyear[ $_POST['per1'] ];
            $type=$types[ $_POST['per2'] ];
            $uyear=$years[ $_POST['per3'] ];
            $semester=$semesters[ $_POST['per4'] ];
            $ndays=$ndayss[ $_POST['per5'] ];
            $action=$actions[ $_POST['per6'] ];         
            get_course_data($id2,$type,$uyear,$semester,$ndays,$action) ;
        echo html_writer::end_tag('div');
        
    echo $OUTPUT->container_end();

    echo $OUTPUT->footer();

    