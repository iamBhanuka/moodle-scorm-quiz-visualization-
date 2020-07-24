<?php

require_once(dirname(__FILE__) . '/../../config.php');

class block_summary extends block_base {
    public function init() {
        $this->title = get_string('summary', 'block_summary');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        global $USER, $COURSE,$OUTPUT;
        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';

        $parameters=array('summaryid'=>$this->instance->id,'userid'=>$USER->id,'courseid'=>$COURSE->id);
        $options = array('class' => 'overviewButton'); 
        $url = new moodle_url('/blocks/summary/overview.php',$parameters);        
        $this->content->text .= $OUTPUT->single_button($url, 'overview of students', 'post',$options);
     
        return $this->content;
    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_summary');            
            } else {
                $this->title = $this->config->title;
            }
     
            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_summary');
            }    
        }
    }

    public function instance_allow_multiple() {
        return true;
      }

      function has_config(){
        return true;
    }

    public function applicable_formats(){
        return array(
            'course-view'    => true,  
            'site'           => true,
            'mod'            => false,
            'my'             => true        
        );
    }

}
