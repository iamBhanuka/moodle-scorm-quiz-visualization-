<?php
class block_attemptone extends block_base {
    public function init() {
        $this->title = get_string('attemptone', 'block_attemptone');
        //Title to be displayed in the header of our block
    }

    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our Attempt1 block!';
        $this->content->footer = 'Footer here...';
     
        return $this->content;

        //$chart = new \core\chart_line();
        //$chart->set_smooth(true);         //for smooth lines
        //$chart->add_series($sales);
        //$chart->add_series($expenses);
        //$chart->set_labels($labels);
        //echo $OUTPUT->render($chart);

    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_attemptone');            
            } else {
                $this->title = $this->config->title;
            }
     
            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_attemptone');
            }    
        }
    }

    public function instance_allow_multiple() {
        return true;
      }

}

