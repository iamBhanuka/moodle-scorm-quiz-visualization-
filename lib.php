<?php

require_once(dirname(__FILE__) . '/../../config.php');
function block_myscorm_course_context($courseid) { //get login page data
    if (class_exists('context_course')) {
        return context_course::instance($courseid);
    } else {
        return get_context_instance(CONTEXT_COURSE, $courseid);
    }
}

function get_login_datas($courseid,$userid ){

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

        
        echo '<select name="scorm" id="dd_scorm" onchange="scormSelect();">';
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

        
        $sql_marks= "SELECT B.*,A.id as scorm_id FROM {scorm} as A INNER JOIN {scorm_scoes_track} as B on A.id=B.scormid AND A.course='$courseid' AND B.element='cmi.core.score.raw' AND B.value<50;";
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
                    echo '<select name="scorm" id="dd_scorm" onchange="scormSelect();">';
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

 $sql_marks= "SELECT B.*,A.id as scorm_id FROM {scorm} as A INNER JOIN {scorm_scoes_track} as B on A.id=B.scormid AND A.course='$courseid' AND B.element='cmi.core.score.raw' AND B.value<50;";
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







                
                }



    //$sql_teacher = "SELECT * FROM {role_assignments} WHERE roleid='3' AND userid='$userid';";
  //  $sql_teacher_res = $DB->get_records_sql($sql_teacher);
//    foreach($sql_teacher_res as $d=>$va){
      //   $contextid = $va->contextid;
    //     $sql_instance_id = "select instanceid from {context} where id=".$contextid." and contextlevel=50;";
  //       $sql_instance_id_res = $DB->get_records_sql($sql_instance_id);
//         foreach($sql_instance_id_res as $record=>$new){
        //     $instanceid = $new->instanceid;
      //       $sql_courses = "select id,shortname,fullname from {course} where id='$instanceid';";
    //         $sql_courses_res = $DB->get_records_sql($sql_courses);
  //           $has_course = false;
//
             foreach($sql_courses_res as $record=>$course){
          //       if($has_course == false){
        //             $has_course = $courseid == $course->id;
      //           }
    //         }
  //           if($has_course){
//                 $sql5= "SELECT id,course,name FROM {scorm} WHERE course=$courseid;";
//                 $login5=$DB->get_records_sql($sql5);
 //                foreach($login5 as $d=>$va){
//                 $c=$va->id;
//                     $sql_marks= "SELECT * FROM {scorm_scoes_track} WHERE element='cmi.core.score.raw' AND scormid='$c' AND value<60;";
 //                   $sql_marks_res = $DB->get_records_sql($sql_marks);
//                     foreach($sql_marks_res as $record=>$mark){
//                         $userid = $mark->userid;
//                         $sql_user= "SELECT * FROM {user} WHERE id=$userid;";
//                        $sql_user_res=$DB->get_records_sql($sql_user);
//                         foreach($sql_user_res as $record=>$user){
//                             echo $user->firstname." ".$user->lastname." ".$mark->value;
 //                            echo "</br>";
//                        }
//                     }
//                 }
//                 return;
//             }
//       }
//
//    }

   echo '</br>';
  $sql5= "SELECT id,course,name FROM {scorm} WHERE course=$courseid;";
  $login5=$DB->get_records_sql($sql5);
  foreach($login5 as $d=>$va){
    echo $a=$va->name;
    $c=$va->id;

      $sql6= "SELECT * FROM {scorm_scoes_track} WHERE element='cmi.core.score.raw' AND scormid='$c' AND userid='$userid' AND userid != 2;";
      $login6=$DB->get_records_sql($sql6);

         $sql8= "SELECT *FROM {user} WHERE id=$userid;" ;
                $login8=$DB->get_records_sql($sql8);
                foreach($login8 as $d0=>$vaa){        
                   $vaa->id.'--'.$vaa->username.'--'.'<br>';
                   $name = $vaa->username;

      if(count($login6)>0){

        $res;
      foreach($login6 as $d=>$va){  
        if($res == NULL){
            $res = $va;
        } else {
            $max = max($res->value,$va->value);
            if($max != $res->value){
                $res->value = $max;
                $res->attempt = $va->attempt;
            }
        }
      }

 // if($userid==$u){
          $attempt= $res->attempt;
          
          $answer = $res->value;
          if($answer >= 101){
              echo $name;
              echo ' Please Do the Quiz';
              return 0;
          }
          if ($answer>=75) {
              echo '<br />';
              echo $name;
              echo ' your attempt';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are Greate';
              echo '<br />';echo '<br />';
              echo '<img src="images/darkGreen.png" height="200" width="500">';
              echo '<br />';
              
              
          }
          elseif($answer>=65){
              echo '<br />';
              echo $name;
              echo ' your attempt ';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are good ';
              echo '<br />';echo '<br />';
              echo '<img src="images/green.png" height="200" width="500">';
              echo '<br />';
              
          }
          elseif($answer>=50){
              echo '<br />';
              echo $name;
              echo ' your attempt ';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are ok ';
              echo '<br />';echo '<br />';
              echo '<img src="images/yellow.png" height="200" width="500">';
              echo '<br />';
              
          }
          elseif($answer>=35){
              echo '<br />';
              echo $name;
              echo ' your attempt ';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are not ok';
              echo '<br />';echo '<br />';
              echo '<img src="images/orange.png" height="200" width="500">';
              echo '<br />';
              
          }
          elseif($answer>=25){
              echo '<br />';
              echo $name;
              echo ' your attempt ';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are bad';
              echo '<br />';echo '<br />';
              echo '<img src="images/red.png" height="200" width="500">';
              
              
          }
          elseif($answer>=0) {
              echo '<br />';
              echo $name;
              echo ' your attempt ';
              echo $attempt;
              echo ' result is ';
              echo $answer;
              echo ' You are very bad and meet supervisore';
              echo '<br />';echo '<br />';
              echo '<img src="images/red.png" height="200" width="500">';
              echo '<br />';
              
          }
          
        }
        else{
            echo '<br />';
            echo $name;
            echo ' Please Do the Quiz';
        }
    }
      }
      }




//   }

 

 
// }



