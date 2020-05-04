<?php

require_once(dirname(__FILE__) . '/../../config.php');

class block_myscorm extends block_base {
    public function init() {
        $this->title = 'Courses scorm data';
    }