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

    $time="SELECT id, FROM_UNIXTIME(timecreated, '%d-%m-%y') as timecreated, userid, objectid/2
            FROM {logstore_standard_log} 
            WHERE ((eventname LIKE '%sco_launched' OR eventname LIKE '%content_pages_viewed')
                AND courseid=$courseid) 
            ORDER BY userid ASC;"; //DESC to ASC
    $result_time = $DB->get_records_sql($time);

    function displayDates($date1, $date2, $format = 'd-m-y' ) {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
            array_push($dates, date($format, $current));
            $current = strtotime($stepVal, $current);
        }
        return $dates;
     }

    $chart = new \core\chart_line();
    //name its axis
    $chart->get_xaxis(0, true)->set_label("Days"); 
    $chart->get_yaxis(0, true)->set_label("Time spent per lesson(hrs)");
    $time=array(0.2, 0.08, 0.01, 0.04, 0.08, 0.37, 0.9, 0.13, 1.2, 0.03, 0, 0.18);
    //$time=array(2, 4, 10, 3, 2, 3, 4, 3, 4, 3, 4, 5, 9, 5, 6, 2, 23, 4, 3, 23, 1, 4, 3, 5, 3, 5, 6, 11, 2, 3);
    $time_per_student = new core\chart_series("time", $time);
    $chart->add_series($time_per_student);
    $date=displayDates("16-6-2020", "27-6-2020");
    $chart->set_labels($date);
    echo $OUTPUT->render($chart);

    echo '</div>';

   echo $OUTPUT->container_end();

   echo $OUTPUT->footer();
