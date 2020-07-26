<?php

defined('MOODLE_INTERNAL') || die();
class block_analytics_graphs extends block_base {
    public function init() {
        $this->title = get_string('analytics_graphs', 'block_analytics_graphs');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        global $CFG;
        global $DB;

        $uselegacypixurl = false; // pix_url got deprecated in Moodle 3.3, leaving this just in case.

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        $canview = has_capability('block/analytics_graphs:viewpages', $context);
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
        // $this->content->text = get_string('graphs', 'block_analytics_graphs');
        $this->content->text = "";
        $this->content->text .= "<li> <a href= {$CFG->wwwroot}/blocks/analytics_graphs/timeaccesseschart.php?id={$course->id}&days=7
                          target=_blank>" . get_string('timeaccesschart_title', 'block_analytics_graphs') . "</a>";
                          
        return $this->content;
    }
}  // Here's the closing bracket for the class definition.
