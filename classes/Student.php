<?php
class Student {

   private $user_id
   private $student_id;
   private $first_name;
   private $last_name;
   private $preferred_name;
   private $grade;
   private $allergies;
   private $medical;
   private $permission_to_leave;
   private $photo_permission;
   
   function __construct($s_id, $u_id, $f_name;
   		$l_name, $p_name, $gr, $al;
   		$med, $leave_perm, $photo_perm) {
   		/*Creates student contact in database.*/
        
   }
   
   function __construct($stu_id) {
   		/*Retrieves student data from db.*/
   		
   }
   
   public function updateStudent {
   		/*Updates student data./*
   
   }

   public function displayStudentForm {
   		/* Display html form with student data. */
   }
      	
   public function updateStudent {
   		/* Updates student data */
   		
   }
   
   public function deleteStudent {
   		/* Deletes student */
   }
   
   static function displayEmptyStudentForm {
   		/* Displays form for student to created. */
   } 
}