<?php

    require_once(dirname(__FILE__) . '/../../config.php');
    
    class block_viewsblock extends block_base {

        public function init(){
            $this->title= 'Views of resourses';
        }

        public function get_content(){
            global $USER, $COURSE,$OUTPUT;

            if($this->content!==null){
                return $this->content;
            }

            $this->content=new stdClass;
            $this->content->text='';
            $this->content->footer='';           
            
            $parameters=array('viewsgraphid'=>$this->instance->id,'userid'=>$USER->id,'courseid'=>$COURSE->id);
             $options = array('class' => 'overviewButton');
             $url = new moodle_url('/blocks/viewsblock/overview.php',$parameters);        
             $this->content->text .= $OUTPUT->single_button($url, 'overview of students', 'post',$options);
            return $this->content;
        }

        public function specialization(){ 
            if(isset($this->config)){ } 
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
        
    }
    
