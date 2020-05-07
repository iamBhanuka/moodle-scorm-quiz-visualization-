<?php

        require_once(dirname(__FILE__) . '/../../config.php');

        // get page context according to id
        function block_viewsblock_course_context($courseid) { 
                if (class_exists('context_course')) {
                        return context_course::instance($courseid);
                } else {
                        return get_context_instance(CONTEXT_COURSE, $courseid);
                }
        }
       