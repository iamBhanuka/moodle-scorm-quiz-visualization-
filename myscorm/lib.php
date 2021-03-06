<html>
    
<head>
    <link rel="stylesheet" href="myscormcss.css" />
</head>

<?php

require_once(dirname(__FILE__) . '/../../config.php');

function block_myscorm_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}


function get_login_datas($courseid,$userid,$minmarks ){
    if($minmarks > 0){
    echo'<br>';
    echo $minmarks ;
    echo ' is your Selected Mark And Please Select the Quiz';
    echo'<br>';
    echo'<br>';

  global $DB;
 

    $sql_courses =  "SELECT C.* FROM {role_assignments} as A INNER JOIN {context} as B on A.contextid=B.id INNER JOIN {course} as C on C.id=B.instanceid AND B.contextlevel=50 AND A.roleid='3'  AND A.userid='$userid' ;";
    $sql_courses_res = $DB->get_records_sql($sql_courses);
    $has_course = false;
    foreach($sql_courses_res as $record=>$course){
        if($has_course == false){
            $has_course = $courseid == $course->id;
        }
    }
    if($has_course){


        $sql_scorm= "SELECT id,name FROM {scorm} WHERE course=$courseid;";        
        $sql_scorm_res = $DB->get_records_sql($sql_scorm);
        // echo '<form action="welcome_get.php" method="get">';
        // echo 'Pass Marks : <input type="text" name="name"><br><br>';
        // echo 'E-mail: <input type="text" name="email"><br>';
        // echo '<input type="submit"><br>';
        // echo '</form>';
        echo '<select name="scorm" id="dd_scorm" onchange="scormSelect();">';
         echo "<option selected>Select Quiz</option>";

         $dropdown_scorm = "";
         echo "<br />";

        foreach($sql_scorm_res as $scorm_res){
            $dropdown_scorm .= "<option value=\"" . $scorm_res->id . "\">" .$scorm_res->name . "</option>";
        }

        echo $dropdown_scorm;
        echo "</select>";

        echo "</br>";

        echo "<div id='scorm_data'></div>";

        // return;

        $data_for_dd_scorm = array();
        

        $sql_marks= "SELECT B.*,A.id as scorm_id FROM {scorm} as A INNER JOIN {scorm_scoes_track} as B on A.id=B.scormid AND A.course='$courseid' AND B.element='cmi.core.score.raw' AND B.value < $minmarks;";
        $sql_marks_res = $DB->get_records_sql($sql_marks);
        foreach($sql_marks_res as $record=>$mark){
            $userid = $mark->userid;
            $sql_user= "SELECT * FROM {user} WHERE id=$userid;";
            $sql_user_res=$DB->get_records_sql($sql_user);
            foreach($sql_user_res as $record=>$user){
                $data["userid"] = $user->id;
                $data["firstname"] = $user->firstname;
                $data["lastname"] = $user->lastname;
                $data["mark"] = $mark->value;
                $data["scorm_id"] = $mark->scorm_id;
                $data["attempt"] = $mark->attempt;
                array_push($data_for_dd_scorm,$data);
            }
        }

        echo "<script>";
        echo "var scormData=" .json_encode($data_for_dd_scorm);
        echo "</script>";
        echo "<script src='js/lib.js'></script>";

        return;
    }

    $sqladmin= "SELECT *FROM {user} WHERE id=$userid;" ;
                $loginadmin=$DB->get_records_sql($sqladmin);
                foreach($loginadmin as $d0=>$vaa){        
                   $vaa->id.'--'.$vaa->username.'--'.'<br>';
                   $name = $vaa->username;
                //    echo "---------------------------------------------------------------";
                //    echo "$userid";
                //    echo"----------------------------------------------------------------------";
                
                if($userid== 2){
              
                $sql_scorm= "SELECT id,name FROM {scorm} WHERE course=$courseid;";        
                $sql_scorm_res = $DB->get_records_sql($sql_scorm);
                
             
     
                
                echo '<select  name="scorm" id="dd_scorm" onchange="scormSelect();">';
                echo "<option selected>Select Quiz</option>";
        
                 $dropdown_scorm = "";
        
                foreach($sql_scorm_res as $scorm_res){
                    $dropdown_scorm .= "<option value=\"" . $scorm_res->id . "\">" .$scorm_res->name . "</option>";
                }
        
                echo $dropdown_scorm;
                echo "</select>";
        
                echo "</br>";
        
                echo "<div id='scorm_data'></div>";

               
        
                // return;
        
                $data_for_dd_scorm = array();
        
                $sql_marks= "SELECT B.*,A.id as scorm_id FROM {scorm} as A INNER JOIN {scorm_scoes_track} as B on A.id=B.scormid AND A.course='$courseid' AND B.element='cmi.core.score.raw' AND B.value < $minmarks;";
                $sql_marks_res = $DB->get_records_sql($sql_marks);
                foreach($sql_marks_res as $record=>$mark){
                    $userid = $mark->userid;
                    $sql_user= "SELECT * FROM {user} WHERE id=$userid;";
                    $sql_user_res=$DB->get_records_sql($sql_user);
                    foreach($sql_user_res as $record=>$user){
                        $data["userid"] = $user->id;
                        $data["firstname"] = $user->firstname;
                        $data["lastname"] = $user->lastname;
                        $data["mark"] = $mark->value;
                        $data["scorm_id"] = $mark->scorm_id;
                        $data["attempt"] = $mark->attempt;
                        array_push($data_for_dd_scorm,$data);
                    }
                }
        
                echo "<script>";
                echo "var scormData=" .json_encode($data_for_dd_scorm);
                echo "</script>";
                echo "<script src='js/lib.js'></script>";
                if($minmarks>=0){
                    $minmarks = 0;
                }
               
        
                return;


            }







                
                }

//    $sql_teacher = "SELECT * FROM {role_assignments} WHERE roleid='3' AND userid='$userid';";
//    $sql_teacher_res = $DB->get_records_sql($sql_teacher);
//    foreach($sql_teacher_res as $d=>$va){
//         $contextid = $va->contextid;
//         $sql_instance_id = "select instanceid from {context} where id=".$contextid." and contextlevel=50;";
//         $sql_instance_id_res = $DB->get_records_sql($sql_instance_id);
//         foreach($sql_instance_id_res as $record=>$new){
//             $instanceid = $new->instanceid;
//             $sql_courses = "select id,shortname,fullname from {course} where id='$instanceid';";
//             $sql_courses_res = $DB->get_records_sql($sql_courses);
//             $has_course = false;
//             foreach($sql_courses_res as $record=>$course){
//                 if($has_course == false){
//                     $has_course = $courseid == $course->id;
//                 }
//             }
//             if($has_course){
//                 $sql5= "SELECT id,course,name FROM {scorm} WHERE course=$courseid;";
//                 $login5=$DB->get_records_sql($sql5);
//                 foreach($login5 as $d=>$va){
//                 $c=$va->id;
//                     $sql_marks= "SELECT * FROM {scorm_scoes_track} WHERE element='cmi.core.score.raw' AND scormid='$c' AND value<60;";
//                     $sql_marks_res = $DB->get_records_sql($sql_marks);
//                     foreach($sql_marks_res as $record=>$mark){
//                         $userid = $mark->userid;
//                         $sql_user= "SELECT * FROM {user} WHERE id=$userid;";
//                         $sql_user_res=$DB->get_records_sql($sql_user);
//                         foreach($sql_user_res as $record=>$user){
//                             echo $user->firstname." ".$user->lastname." ".$mark->value;
//                             echo "</br>";
//                         }
//                     }
//                 }
//                 return;
//             }
//         }

//    }
    }
      }




//   }

 

 
// }
