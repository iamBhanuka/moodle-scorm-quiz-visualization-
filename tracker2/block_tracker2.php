<?php

require_once(dirname(__FILE__) . '/../../config.php');

class block_tracker2 extends block_base {
    public function init() {
        $this->title = get_string('tracker2', 'block_tracker2');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        
        global $USER, $COURSE, $OUTPUT; 
        
        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;

        $parameters     = array('tracker2id' => $this->instance->id, 'userid' => $USER->id, 'courseid' => $COURSE->id);
        $options        = array('class' => 'overviewButton');
        $url            = new moodle_url('/blocks/tracker2/overview.php', $parameters);        
        $this->content->text .= $OUTPUT->single_button($url, 'view graph', 'post', $options);
        //$this->content->text .= html_writer::link($url, 'access details', array('class' => 'btn btn-secondary'));


        return $this->content;
    }
    public function instance_allow_multiple() {
        return true;
      }
    


    function has_config() {return true;}

    public function hide_header() {
        return false;
    }

    public function applicable_formats() {
        return array(
            'course-view' => true
        );
    }
}