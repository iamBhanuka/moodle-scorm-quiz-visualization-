<?php

require_once(dirname(__FILE__) . '/../../config.php');


class block_coursegraph extends block_base {

    public function init(){
        $this->title='Course Overview';
    }

    public function specialization(){
        if(isset($this->config)){
           
        }
   }

   public function instance_allow_multiple(){
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

    public function get_content(){
        global $USER, $COURSE, $CFG, $OUTPUT, $DB;

         if($this->content!==null){
             return $this->content;
         }

         $this->content=new stdClass;
         $this->content->text='';
         $this->content->footer='';
         
       
//          $parameters=array( 'coursegraphid' => $this->instance->id,'courseid' => $COURSE->id,'userid'=>$USER->id);
//          $options1 = array('class' => 'overviewButton');
//          $options2 = array('class' => 'overviewButton');
//          $url1 = new moodle_url('/blocks/coursegraph/overview.php',$parameters);
//          $url2 = new moodle_url('/blocks/coursegraph/grades.php',$parameters);
//          $this->content->text .= $OUTPUT->single_button($url1, 'My Activities', 'post',$options1);
//          $this->content->text .= $OUTPUT->single_button($url2, 'My Marks', 'post',$options2);
         
//          return $this->content;
     }

    
      
}
 
