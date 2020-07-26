<?php

defined('MOODLE_INTERNAL') || die();

function block_analytics_graphs_get_students($course) {
    global $DB;
    $students = array();
    $context = context_course::instance($course);
    $allstudents = get_enrolled_users($context, 'block/analytics_graphs:bemonitored', 0,
                    'u.id, u.firstname, u.lastname, u.email, u.suspended', 'firstname, lastname');
    foreach ($allstudents as $student) {
        if ($student->suspended == 0) {
            if (groups_user_groups_visible($DB->get_record('course', array('id' =>  $course), '*', MUST_EXIST), $student->id)) {
                $students[] = $student;
            }
        }
    }
    return($students);
}

function block_analytics_graphs_get_number_of_days_access_by_week($course, $estudantes, $startdate, $legacy=0) {
    global $DB;
    $timezone = new DateTimeZone(core_date::get_server_timezone());
    $timezoneadjust   = $timezone->getOffset(new DateTime);
    foreach ($estudantes as $tupla) {
        $inclause[] = $tupla->id;
    }
    list($insql, $inparams) = $DB->get_in_or_equal($inclause);
    $params = array_merge(array($timezoneadjust, $timezoneadjust, $startdate, $course, $startdate), $inparams);
    if (!$legacy) {
        $sql = "SELECT temp2.userid+(week*1000000) as id, temp2.userid, firstname, lastname, email, week,
                number, numberofpageviews
                FROM (
                    SELECT temp.userid, week, COUNT(*) as number, SUM(numberofpageviews) as numberofpageviews
                    FROM (
                        SELECT MIN(log.id) as id, log.userid,
                            FLOOR((log.timecreated + ?)/ 86400)   as day,
                            FLOOR( (((log.timecreated  + ?) / 86400) - (?/86400))/7) as week,
                            COUNT(*) as numberofpageviews
                        FROM {logstore_standard_log} log
                        WHERE courseid = ? AND action = 'viewed' AND target = 'course'
                            AND log.timecreated >= ? AND log.userid $insql
                        GROUP BY userid, day, week
                    ) as temp
                    GROUP BY week, temp.userid
                ) as temp2
                LEFT JOIN {user} usr ON usr.id = temp2.userid
                ORDER BY LOWER(firstname), LOWER(lastname),userid, week";
    } else {
        $sql = "SELECT temp2.userid+(week*1000000) as id, temp2.userid, firstname, lastname, email, week,
                number, numberofpageviews
                FROM (
                    SELECT temp.userid, week, COUNT(*) as number, SUM(numberofpageviews) as numberofpageviews
                    FROM (
                        SELECT MIN(log.id) as id, log.userid,
                            FLOOR((log.time + ?)/ 86400)   as day,
                            FLOOR( (((log.time  + ?) / 86400) - (?/86400))/7) as week,
                            COUNT(*) as numberofpageviews
                        FROM {log} log
                        WHERE course = ? AND action = 'view' AND module = 'course'
                            AND log.time >= ? AND log.userid $insql
                        GROUP BY userid, day, week
                    ) as temp
                    GROUP BY week, temp.userid
                ) as temp2
                LEFT JOIN {user} usr ON usr.id = temp2.userid
                ORDER BY LOWER(firstname), LOWER(lastname),userid, week";
    }
    $resultado = $DB->get_records_sql($sql, $params);
    return($resultado);
}

function block_analytics_graphs_get_accesses_last_days($course, $estudantes, $daystoget) {
    global $DB;
    $date = strtotime(date('Y-m-d', strtotime('-'. $daystoget .' days')));
    $sql = "SELECT s.id, s.action, s.target, s.userid, s.courseid, s.timecreated, usr.firstname, usr.lastname
            FROM {logstore_standard_log} s
            LEFT JOIN {user} usr ON s.userid = usr.id
            WHERE s.courseid = " . $course . " AND s.timecreated >= " . $date . "
            AND (";
    $iterator = 0;
    foreach ($estudantes as $item) {
        if ($iterator == 0) {
            $sql .= " s.userid = " . $item->id;
        } else {
            $sql .= " OR s.userid = " . $item->id;
        }
        $iterator++;
    }
    $sql .= " )
             ORDER BY s.timecreated";
    $resultado = $DB->get_records_sql($sql);

    foreach ($resultado as $item) {
        $item->timecreated = date("His", $item->timecreated);
    }

    return($resultado);
}

function block_analytics_graphs_get_number_of_modules_access_by_week($course, $estudantes, $startdate, $legacy=0) {
    global $DB;
    $timezone = new DateTimeZone(core_date::get_server_timezone());
    $timezoneadjust   = $timezone->getOffset(new DateTime);
    foreach ($estudantes as $tupla) {
        $inclause[] = $tupla->id;
    }
    list($insql, $inparams) = $DB->get_in_or_equal($inclause);
    $params = array_merge(array($timezoneadjust, $startdate, $course, $startdate), $inparams);
    if (!$legacy) {
        $sql = "SELECT userid+(week*1000000), userid, firstname, lastname, email, week, number
                FROM (
                    SELECT  userid, week, COUNT(*) as number
                    FROM (
                        SELECT log.userid, objecttable, objectid,
                        FLOOR((((log.timecreated + ?) / 86400) - (?/86400))/7) as week
                        FROM {logstore_standard_log} log
                        WHERE courseid = ? AND action = 'viewed' AND target = 'course_module'
                        AND log.timecreated >= ? AND log.userid $insql
                        GROUP BY userid, week, objecttable, objectid
                    ) as temp
                    GROUP BY userid, week
                ) as temp2
                LEFT JOIN {user} usr ON usr.id = temp2.userid
                ORDER by LOWER(firstname), LOWER(lastname), userid, week";
    } else {
        $sql = "SELECT userid+(week*1000000), userid, firstname, lastname, email, week, number
                FROM (
                    SELECT  userid, week, COUNT(*) as number
                    FROM (
                        SELECT log.userid, module, cmid,
                        FLOOR((((log.time + ?) / 86400) - (?/86400))/7) as week
                        FROM {log} log
                        WHERE course = ? AND (action = 'view' OR action = action = 'view forum')
                            AND module <> 'assign' AND cmid <> 0 AND time >= ? AND log.userid $insql
                        GROUP BY userid, week, module, cmid
                    ) as temp
                    GROUP BY userid, week
                ) as temp2
                LEFT JOIN {user} usr ON usr.id = temp2.userid
                ORDER by LOWER(firstname), LOWER(lastname), userid, week";
    }
    $resultado = $DB->get_records_sql($sql, $params);
    return($resultado);
}

function block_analytics_graphs_get_number_of_modules_accessed($course, $estudantes, $startdate, $legacy=0) {
    global $DB;
    foreach ($estudantes as $tupla) {
        $inclause[] = $tupla->id;
    }
    list($insql, $inparams) = $DB->get_in_or_equal($inclause);
    $params = array_merge(array($course, $startdate), $inparams);
    if (!$legacy) {
        $sql = "SELECT userid, COUNT(*) as number
            FROM (
                SELECT log.userid, objecttable, objectid
                FROM {logstore_standard_log} log
                LEFT JOIN {user} usr ON usr.id = log.userid
                WHERE courseid = ? AND action = 'viewed' AND target = 'course_module'
                    AND log.timecreated >= ? AND log.userid $insql
                GROUP BY log.userid, objecttable, objectid
            ) as temp
            GROUP BY userid
            ORDER by userid";
    } else {
        $sql = "SELECT userid, COUNT(*) as number
            FROM (
                SELECT log.userid, module, cmid
                FROM {log} log
                LEFT JOIN {user} usr ON usr.id = log.userid
                WHERE course = ? AND (action = 'view' OR action = 'view forum')
                    AND module <> 'assign' AND cmid <> 0  AND log.time >= ? AND log.userid $insql
                GROUP BY log.userid, module, cmid
            ) as temp
            GROUP BY userid
            ORDER by userid";
    }
    $resultado = $DB->get_records_sql($sql, $params);
    return($resultado);
}

function block_analytics_graphs_get_course_name($course) {
    global $DB;
    $sql = "SELECT
              a.fullname
            FROM
              {course} a
            WHERE
              a.id = " . $course;
    $result = $DB->get_records_sql($sql);

    $resultname = "";

    foreach ($result as $item) {
        if (!empty($item)) {
            $resultname = $item->fullname;
        }
    }

    return $resultname;
}

function block_analytics_graphs_get_logstore_loglife() {
    global $DB;
    $sql = "SELECT  a.id, a.plugin, a.name, a.value
                FROM {config_plugins} a
                WHERE a.name = 'loglifetime' AND a.plugin = 'logstore_standard'
                ORDER BY name";
    $result = $DB->get_records_sql($sql);
    return reset($result)->value;
}

function block_analytics_graphs_get_course_days_since_startdate($course) {
    global $DB;
    $sql = "SELECT  a.id, a.startdate
                FROM {course} a
                WHERE a.id = " . $course;
    $result = $DB->get_records_sql($sql);
    $startdate = reset($result)->startdate;
    $currentdate = time();
    return floor(($currentdate - $startdate) / (60 * 60 * 24));
}

function block_analytics_graphs_extend_navigation_course($navigation, $course, $context) {
    global $CFG;
    global $DB;
    $reports = $navigation->find('coursereports', navigation_node::TYPE_CONTAINER);
    if (has_capability('block/analytics_graphs:viewpages', $context) && $reports) {
        $sql = "SELECT cm.module, md.name
            FROM {course_modules} cm
            LEFT JOIN {modules} md ON cm.module = md.id
            WHERE cm.course = ?
            GROUP BY cm.module, md.name";
        $params = array($course->id);
        $availablemodulestotal = $DB->get_records_sql($sql, $params);
        $availablemodules = array();
        foreach ($availablemodulestotal as $result) {
            array_push($availablemodules, $result->name);
        }

        $reportanalyticsgraphs = $reports->add(get_string('pluginname', 'block_analytics_graphs'));

        $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/grades_chart.php',
            array('id' => $course->id));
        $reportanalyticsgraphs->add(get_string('grades_chart', 'block_analytics_graphs'), $url,
            navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));

        $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/graphresourcestartup.php',
            array('id' => $course->id, 'legacy' => '0'));
        $reportanalyticsgraphs->add(get_string('access_to_contents', 'block_analytics_graphs'), $url,
            navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
        $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/timeaccesseschart.php',
            array('id' => $course->id, 'days' => '7'));
        $reportanalyticsgraphs->add(get_string('timeaccesschart_title', 'block_analytics_graphs'), $url,
            navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));

        if (in_array("assign", $availablemodules)) {
            $url = new moodle_url($CFG->wwwroot . '/blocks/analytics_graphs/assign.php',
                array('id' => $course->id));
            $reportanalyticsgraphs->add(get_string('submissions_assign', 'block_analytics_graphs'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
        }

        if (in_array("quiz", $availablemodules)) {
            $url = new moodle_url($CFG->wwwroot . '/blocks/analytics_graphs/quiz.php', array('id' => $course->id));
            $reportanalyticsgraphs->add(get_string('submissions_quiz', 'block_analytics_graphs'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
        }

        if (in_array("hotpot", $availablemodules)) {
            $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/hotpot.php', array('id' => $course->id));
            $reportanalyticsgraphs->add(get_string('submissions_hotpot', 'block_analytics_graphs'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
        }
        
        if (in_array("turnitintooltwo", $availablemodules)) {
            $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/turnitin.php', array('id' => $course->id));
            $reportanalyticsgraphs->add(get_string('submissions_turnitin', 'block_analytics_graphs'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
        }

        $url = new moodle_url($CFG->wwwroot.'/blocks/analytics_graphs/hits.php', array('id' => $course->id,
            'legacy' => '0'));
        $reportanalyticsgraphs->add(get_string('hits_distribution', 'block_analytics_graphs'), $url,
            navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
    }
}
