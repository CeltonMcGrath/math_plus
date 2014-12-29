<?php
include 'Program.php';

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
   		/* Returns student object with student id s_id */
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
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
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
		/* Prints the name of the student: 'First name Last name' */
		echo $this->first_name." ".$this->last_name;
	}
 
	public function displayStudentInfo() {
   		/* Student display for students.php */
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
		   		echo "<form action='programs.php' method='post'>
 						<input type='hidden' name='student_id' 
 							value=".$this->student_id."/>
 						<input type='submit' value='Add or view programs' />
 					</form>
 				</article>
	   		</div>";
   }
   
	private function displayStudentForm() {
		/* Displays student form for students.php*/
   		$photo_check = '';
		if ($this->photo_permission) {
   			$photo_check = 'checked';
   		}
		$leave_check = '';
		if ($this->permission_to_leave) {
   			$leave_check = 'checked';
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
					<input class='regular 'type='checkbox' 
 						name='permission_to_leave' ".$leave_check."/>
   					<br /><br />
   					Photo permission: 
   					<input class='regular 'type='checkbox' 
 						name='photo_permission' ".$photo_check."/>
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
							<input class='regular 'type='checkbox' 
		 						name='permission_to_leave' />
		   					<br /><br />
		   					Photo permission: 
		   					<input class='regular 'type='checkbox' 
		 						name='photo_permission' />
		   					<br /><br />
						    <br />
						    <input type='submit' value='Submit' />
						</form> 
    				</article>
    			</div>";
   }
   
    private function displayFutureProgramList() {
    	/* Print a html list of programs this student is enrolled in that 
    	 * end in the future.*/
    	
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
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
 		}
 		$rows = $stmt->fetchAll();
 			
 		foreach($rows as $row):
 			echo "<li>".$row['program_name']." 
 				(".$row['programs.start_date']."-".$row['programs.end_date'].")
 				(Status: ".$row['students_programs.status'].")</li>";
 		endforeach;
 		echo "</ul>";
 	}  
 	
 	public function displayAllPrograms() {
 		/* Display accordion-style list of programs for programs.php */
 		
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
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
 		}	
 		$programRows = $stmt->fetchAll();
		
 		foreach($programRows as $programRow):
 			$program = new Program($programRow['program_id'], $this->database);
 			$status = $this->programStatus($program->program_id);
 			if ($status) {
 				$program->displayProgramForSelectionTwo($status);
 			}
 			else {
 				$program->displayProgramForSelectionOne();
 			}
 		endforeach;
 	}
 	
 	/* Returns status of registration if student in program and 
 	 * false if the student is not registered in the program.
 	 */
 	private function programStatus($program_id) {
 		$query = 'SELECT status
	   			FROM
 					students_programs
	   			WHERE
 					students_programs.student_id = :student_id
 					and students_programs.program_id = :program_id';
 				
 		$query_params = array(
 				':student_id' => $this->student_id,
 				':program_id' => $program_id,
 		);
 		
 		try	{
 			// Execute the query against the database
 			$stmt = $this->database->prepare($query);
 			$result = $stmt->execute($query_params);
 		} catch(PDOException $ex) {
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
 		}
 		$row = $stmt->fetch();
 		
 		if ($row) {
 			return $row['status'];
 		}
 		else {
 			return false;
 		} 
 	}
 	
 	/* Updates database record of student and returns true iff
 	 * update successful. 
 	 * */
	public static function updateStudent($s_id, $p_name, 
    				$gr, $all, $med, $leave_perm, $photo_perm, $db) {
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
   
	public function programCartDisplay($program_id, $counter) {
	   	$program = new Program($program_id, $this->database);
	   	$program->displayForCart($this->first_name." ".$this->last_name, $counter);
   		return $program->getCost();
   		
	}

}
   
