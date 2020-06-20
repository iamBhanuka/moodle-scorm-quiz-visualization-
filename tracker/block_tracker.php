<?php

require_once(dirname(__FILE__) . '/../../config.php');

class block_tracker extends block_base {
    public function init() {
        $this->title = get_string('tracker', 'block_tracker');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        
        global $USER, $COURSE, $OUTPUT; 
        
        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;

        $parameters     = array('logingraphid' => $this->instance->id, 'userid' => $USER->id, 'courseid' => $COURSE->id);
        $options        = array('class' => 'overviewButton');
        $url            = new moodle_url('/blocks/tracker/overview.php', $parameters);        
        $this->content->text .= $OUTPUT->single_button($url, 'access details', 'post', $options);
        //$this->content->text .= html_writer::link($url, 'access details', array('class' => 'btn btn-secondary'));


        return $this->content;
    }
    public function instance_allow_multiple() {
        return true;
      }
    
      public function specialization(){ 
        if(isset($this->config)){ } 
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