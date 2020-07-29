<?php

defined('MOODLE_INTERNAL') || die();
class block_tracker5 extends block_base {
    public function init() {
        $this->title = get_string('tracker5', 'block_tracker5');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        global $CFG;
        global $DB;

        $uselegacypixurl = false; // pix_url got deprecated in Moodle 3.3, leaving this just in case.

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        $canview = has_capability('block/tracker5:viewpages', $context);
        if (!$canview) {
            return;
        }
        if ($this->content !== null) {
            return $this->content;
        }

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

        $this->content = new stdClass;
        // $this->content->text = get_string('graphs', 'block_tracker5');
        $this->content->text = "";
        $this->content->text .= "<li> <a href= {$CFG->wwwroot}/blocks/tracker5/timeaccesseschart.php?id={$course->id}&days=7
                          target=_blank>" . "View graph" . "</a>";
        // $parameters     = array('tracker5id' => $this->instance->id, 'userid' => $USER->id, 'courseid' => $COURSE->id);
        // $options        = array('class' => 'overviewButton');
        // $url = new moodle_url('/blocks/tracker5/overview.php', $parameters);        
        // $this->content->text .= $OUTPUT->single_button($url, 'view graph', 'post', $options);
                          
        return $this->content;
    }
}  
