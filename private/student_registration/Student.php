<?php
class Student {
	
   public $student_id;
   private $user_id;
   private $first_name;
   private $last_name;
   private $preferred_name;
   private $grade;
   private $allergies;
   private $medical;
   private $permission_to_leave;
   private $photo_permission;
   private $database;    
 
   public function __construct($s_id, $db) {
   		$this->student_id = $s_id;
   		$this->database = $db;
   		
   		/*Retrieves student data from db.*/
	   	$query = 'SELECT first_name, last_name, preferred_name, grade, 
	   			allergies, medical, permission_to_leave, photo_permission
	   			FROM students
	    		WHERE student_id = :student_id';
	   	
	   	$query_params = array(':student_id' => $this->student_id);
	   	 
	   	try {
	   		// Execute the query against the database
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		return False;
	   	}
	   	$row = $stmt->fetch();
	   	
	   	$this->first_name = $row['first_name']; 
	   	$this->last_name = $row['last_name']; 
	   	$this->preferred_name = $row['preferred_name']; 
	   	$this->grade = $row['grade']; 
	   	$this->allergies = $row['allergies']; 
	   	$this->medical = $row['medical'];
	   	$this->permission_to_leave = $row['permission_to_leave'];
	   	$this->photo_permission = $row['photo_permission'];
   }

   public static function createStudent($u_id, $f_name,
   		$l_name, $p_name, $gr, $all, $med, $perm_leave, 
   		$perm_photo, $db) {
   	    /*Creates student contact in database.*/
   		
   		$query = "INSERT INTO students (user_id, first_name, last_name, 
   				preferred_name, grade, allergies, medical, permission_to_leave,
   				photo_permission) 
	   			VALUES
				(:user_id, :first_name, :last_name, 
   				:preferred_name, :grade, :allergies, :medical, 
   				:permission_to_leave, :photo_permission)";
	   	 
	   	$query_params = array(
	   			':user_id' => $u_id,
	   			':first_name' => $f_name,
	   			':last_name' => $l_name,
	   			':preferred_name' => $p_name,
	   			':grade' => $gr,
	   			':allergies' => $all,
	   			':medical' => $med,
	   			':permission_to_leave' => $perm_leave,
	   			':photo_permission' => $perm_photo
	   	);
	   	
	   	try	{
	   		// Execute the query against the database
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	return True;
   }
  
   public function printName() {
   	echo $this->first_name." ".$this->last_name;
   }
 
   public function displayStudentInfo() {
    	echo "
	    	<div class='contact'>
		   		<input class='accordion' type='checkbox'
		   			id=".$this->student_id." />
		   		<label for=".$this->student_id.">
		   			".$this->last_name.", ".$this->first_name."
		   		</label>
		   		<article>";
					$this->displayStudentForm();
					echo "<br />";
		   			$this->displayFutureProgramList();
		   			echo "<br />";
		   			$this->displayPastProgramList();
		   		echo "</article>
	   		</div>";
   }
   
   private function displayStudentForm() {
   		if ($this->photo_permission=="yes") {
   			$photo_yes = 'checked';
   			$photo_no = '';
   		}
   		else {
   			$photo_yes = '';
   			$photo_no = 'checked';
   		}

		if ($this->permission_to_leave=="yes") {
   			$leave_yes = 'checked';
   			$leave_no = '';
   		}
   		else {
   			$leave_yes = '';
   			$leave_no = 'checked';
   		} 
   		
   		echo "<form action='students.php' method='post'> 
   	    			<input type='hidden' name='student_id' 
	   						value='$this->student_id'/>
   					Preferred name:<input type='text' name='preferred_name' 
   	    				value='$this->preferred_name'/> 
   					<br />
   					Grade:<input type='text' name='grade' 
   	    				value='$this->grade'/> 
   					<br />
   					Allergies:<input type='text' name='allergies' 
   	    				value='$this->allergies'/> 
   					<br />
   					Medical:<input type='text' name='medical' 
   	    				value='$this->medical'/>
   					<br /> 
					Permission to leave: 
					<input type='radio' name='permission_to_leave' 
 								value='yes' ".$leave_yes."/> Yes
					<input type='radio' name='permission_to_leave' 
 								value='no' ".$leave_no."/> No
						    <br />
   					Photo permission: 
   					<input type='radio' name='photo_permission' 
   	    						value='yes' ".$photo_yes."/> Yes
   					<input type='radio' name='photo_permission' 
   	    						value='no' ".$photo_no."/> No
   					<br /><br />
   					Delete student: <input type='radio' name='delete' 
   	    				value='yes'/> Yes
   					<input type='radio' name='delete' 
   	    				value='no' checked/> No         
   					<br />
   					<input type='submit' value='Submit Changes' />
   	    		</form>";			
   }
   
   public static function displayEmptyStudentForm() {
 		echo "
				<div class='contact'>
    				<input class='accordion' type='checkbox' id='check-0' />
    				<label for='check-0'>Add new student</label>
    				<article>
    					<form action='students.php' method='post'> 
    						<input type='hidden' name='student_id' value='0' />
				    		First name: <input type='text' name='first_name'/> 
						    <br />
						    Last name: <input type='text' name='last_name'/> 
						    <br />
						    Preferred name:<input type='text' 
								name='preferred_name'/> 
						    <br />
						    Grade:<input type='text' name='grade'/> 
						    <br />
						    Allergies:<input type='text' name='allergies'/> 
						    <br />
						    Medical:<input type='text' name='medical'/>
						    <br /> 
 							Permission to leave: 
						    <input type='radio' name='permission_to_leave' 
 								value='yes'/> Yes
						    <input type='radio' name='permission_to_leave' 
 								value='no' checked/> No
						    <br />
						    Photo permission: 
						    <input type='radio' name='photo_permission' 
 								value='yes' checked> Yes
						    <input type='radio' name='photo_permission' 
 								value='no'> No
						    <br />
						    <input type='submit' value='Submit' />
						</form> 
    				</article>
    			</div>";
   }
   
 	private function displayFutureProgramList() {
 		// Generate list of upcoming programs student is registered in.
 		echo "Upcoming programs: <ul>";
 		// Query the db for guardian contacts associated with current user
 		$query = 'SELECT 
 					programs.program_name, programs.start_date, 
 					programs.end_date, students_programs.status
	   			FROM 
 					students_programs INNER JOIN programs
	   			on 
 					students_programs.program_id = programs.program_id
	   			WHERE 
 					student_id = :student_id AND
 					NOW() < programs.end_date';
 		
 		$query_params = array(
 				':student_id' => $this->student_id
 		);
 		
 		try {
 			// Execute the query against the database
 			$stmt = $this->database->prepare($query);
 			$result = $stmt->execute($query_params);
 		} catch(PDOException $ex) {
 			die('Failed to run query: ' . $ex->getMessage());
 		}
 		$rows = $stmt->fetchAll();
 			
 		foreach($rows as $row):
 			echo "<li>".$row['program_name']." 
 				(".$row['programs.start_date']."-".$row['programs.end_date'].")
 				(Status: ".$row['students_programs.status'].")</li>";
 		endforeach;
 		echo "</ul>							
 		<form action='programs.php' method='post'>
 		<input type='hidden' name='student_id' 
 			value=".$this->student_id."/>
 		<input type='submit' value='Add or view programs' />
 		</form>";
 	}  
 	
 	public function displayAllPrograms() {
 		//Select all upcoming programs
 		$query = 'SELECT *
	   			FROM
 					programs
	   			WHERE
 					NOW() < programs.registration_deadline';
 		try {
 			// Execute the query against the database
 			$stmt = $this->database->prepare($query);
 			$result = $stmt->execute();
 		} catch(PDOException $ex) {
 			die('Failed to run query: ' . $ex->getMessage());
 		}
 		
 		$rows = $stmt->fetchAll();
 		
 		foreach($rows as $row):
 				
 		endforeach;
 	}

	public static function updateStudent($s_id, $p_name, 
    				$gr, $all, $med, $leave_perm, $photo_perm, $db) {
		/* Updates guardian data and returns true iff success. */
		$query = "UPDATE students
	    		SET preferred_name = :preferred_name, grade = :grade, 
					allergies = :allergies, medical = :medical,
					photo_permission = :photo_permission,
					permission_to_leave = :permission_to_leave
	    		WHERE student_id = :student_id";
		
		$query_params = array (
				':preferred_name' => $p_name,
				':grade' => $gr,
				':allergies' => $all,
				':medical' => $med,
				'photo_permission' => $photo_perm,
				'permission_to_leave' => $leave_perm,
				'student_id' => $s_id
		);
		
		try {
			// Execute the query against the database
			$stmt = $db->prepare ( $query );
			$result = $stmt->execute ( $query_params );
		} catch ( PDOException $ex ) {
			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
		}
		return True;
	}
     	
   public static function deleteStudent($s_id, $db) {
   		/* Deletes student from database.*/
	   	$query = "DELETE FROM students
	    		WHERE student_id = :student_id";
	   	
	   	$query_params = array(':student_id' => $s_id);
	   	
	   	try {
	   		// Execute the query against the database
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex)	{
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	return True;
   }
   
   public function displayPastProgramList() {
   		return True;
   }

}
   
