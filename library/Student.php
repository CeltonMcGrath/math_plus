<?php
include 'Program.php';
include 'Guardian.php';

class Student {

	private $student_id;
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
 
	/*---------------------------------------------------------------
	 *  Constructors
	 * ---------------------------------------------------------------*/
	
	/* Returns student object with student id s_id */
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

	/*Creates student contact in database.*/
	public static function createStudent($u_id, $f_name,
   		$l_name, $p_name, $gr, $all, $med, $perm_leave, 
   		$perm_photo, $db) {	    
   		
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
  	
   /*---------------------------------------------------------------
    *  Getter and printer functions
    * ---------------------------------------------------------------*/
   
  	/* Prints this student's ID. */
	public function getId() {
		return $this->student_id;
	}
   
    /* Prints the name of the student: 'First name Last name' */
	public function printName() {		
		echo $this->first_name." ".$this->last_name;
	}
	
	public function getName() {
		return $this->first_name." ".$this->last_name;
	}
 	
	/*---------------------------------------------------------------
	 *  Functions which display individualized contents of student 
	 *  contact in students.php
	 * ---------------------------------------------------------------*/
	
	/* Student display for students.php */
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
		   			echo "
		   			<form action='programs.php' method='post'>
 						<input type='hidden' name='student_id' 
 							value=".$this->student_id."/>
 						<input type='submit' value='Add or view programs'/>
 					</form>
 				</article>
	   		</div>";
  	 }	
   
	/* Displays student form for students.php*/
	private function displayStudentForm() {
		$text_field = $GLOBALS['text_field'];
   		$photo_check = '';
		if ($this->photo_permission) {
   			$photo_check = 'checked';
   		}
		$leave_check = '';
		if ($this->permission_to_leave) {
   			$leave_yes = 'checked';
   			$leave_no = '';
   		}
   		else {
   			$leave_yes = '';
   			$leave_no = 'checked';
   		}
   		
   		echo "
   		<form action='students.php' method='post'> 
   	    	<input type='hidden' name='student_id' value='$this->student_id'/>
   			Preferred name: 
   			<input type='text' name='preferred_name' 
   				value='$this->preferred_name'/> 
   			Grade:
   			<input type='text' name='grade' value='$this->grade'/> 
   			<br />
	   		".$text_field['allergy_label']."
   			<textarea name='allergies'>".$this->allergies."</textarea> 
   			".$text_field['medical_label'].":
   			<textarea name='medical'>".$this->medical."</textarea>
   			<br />
   			<input class='regular 'type='checkbox' name='photo_permission' 
   				".$photo_check."/> ".$text_field['photo_perm_label']."   			 
   			<br /><br /> 
   			<input class='regular 'type='checkbox'
   				name='leave_permission[]' ".$leave_no." />
   			".$text_field['leave_perm_no']."
   			<input class='regular 'type='checkbox'
   				name='leave_permission[]' ".$leave_yes." />
   			".$text_field['leave_perm_yes']."
   			<br /><br />";
   			$this->displayGuardianPickupSelection();	
   			echo "<br /><br />
	 			<input type='checkbox' class='regular' name='consent' 
   					checked />
	   			".$text_field['student_consent']."
	 			<br /><br />
	   			<input type='submit' value='Submit Changes' />
	   	    </form>";
   }
   
   	/* Displays selection area for which guardians can pick-up student. */
	private function displayGuardianPickupSelection() {
   		return true;
	}
	  
   /* Print a html list of programs this student is enrolled in that
    * end in the future.*/
    private function displayFutureProgramList() {	
 		echo "Upcoming programs: <ul>";
 		
 		$query = 'SELECT 
 					programs.program_name, programs.start_date, 
 					programs.end_date
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
 				<i>Registered</i></li>";
 		endforeach;
 		echo "</ul>";
 	}  
 	
 	/* Print a html list of programs this student is enrolled in that
 	 * ended in the past. */
 	public function displayPastProgramList() {
 		echo "Past programs: <ul>";
 		
 		$query = "SELECT 
 					programs.program_name, programs.start_date, 
 					programs.end_date
	   			FROM 
 					students_programs INNER JOIN programs
	   			on 
 					students_programs.program_id = programs.program_id
	   			WHERE 
 					student_id = :student_id AND
 					programs.end_date < NOW()";
 		
 		$query_params = array(
 				':student_id' => $this->student_id
 		);
 		
 		try {
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
 				</li>";
 		endforeach;
 		echo "</ul>";
 	}
 	
 	/*---------------------------------------------------------------
 	 *  Functions which are used for programs.php
 	 *  (Individualized display for each student.)
 	 * ---------------------------------------------------------------*/
 	
 	/* Display accordion-style list of programs for programs.php */
 	public function displayAllPrograms() {		
 		//Select all upcoming programs
 		$query = 'SELECT *
	   			FROM
 					programs
	   			WHERE
 					NOW() < programs.registration_deadline';
 		try {
 			$stmt = $this->database->prepare($query);
 			$result = $stmt->execute();
 		} catch(PDOException $ex) {
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
 		}	
 		$programRows = $stmt->fetchAll();
		
 		if (count($programRows)==0) {
			echo "No upcoming programs. Check back again!";
			return true;
		}

		foreach($programRows as $programRow):
 			$program = new Program($programRow['program_id'], $this->database);
 			echo "<div class='contact'>
 					<input class='accordion' type='checkbox' 
 					id='".$program->program_id."'/>";
 			// Label
 			if ($this->inProgram($program->program_id)) {
 				$program->displayLabelForSelectionTwo();
 			}
 			elseif ($program->remainingSpots() == 0) {
 				$program->displayLabelForSelectionThree();
 			}
 			else {
 				$program->displayLabelForSelectionOne();
 			}
 			// Article
 			$program->displayArticle();
 		endforeach;
 	}
 	
 	// Returns true iff student is registered in program.
 	private function inProgram($program_id) {
 		$query = 'SELECT *
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
 		
 		return !empty($row); 
 	}

 	/*---------------------------------------------------------------
 	 *  Static functions which allow creation of new students or
 	 *  updates of students in students.php
 	 * ---------------------------------------------------------------*/
 	
 	/* Displays empty student form. */
 	public static function displayEmptyStudentForm($db, $user_id) {
 		$text_field = $GLOBALS['text_field'];
 		echo "
			<div class='contact'>
    		<input class='accordion' type='checkbox' id='check-0' />
    		<label for='check-0'>Add new student</label>
    		<article>
    			<form action='students.php' method='post'>
	   	    	<input type='hidden' name='student_id' value='0'/>
	   	    	First name:
	   			<input type='text' name='first_name' />
	   			Last name:
	   			<input type='text' name='last_name' '/>
	   			Preferred name:
	   			<input type='text' name='preferred_name' />
	   			Grade:
	   			<input type='text' name='grade' />
				<br />
	   			".$text_field['allergy_label']."
	   			<textarea name='allergies' row='3'></textarea>
	   			".$text_field['medical_label']."
	   			<textarea name='medical' row='3'></textarea>
	   			<br />
	   			<input class='regular 'type='checkbox'
	   				name='photo_permission' />
	   			".$text_field['photo_perm_label']."
	   			<br /><br />
 				<input class='regular 'type='checkbox'
 					name='leave_permission[]' id='leave_no' />
 				".$text_field['leave_perm_no']."
 				<input class='regular 'type='checkbox'
 					name='leave_permission[]' id='leave_yes' />
 				".$text_field['leave_perm_yes']."
 				<br /><br />";			
 		self::displayNewGuardianPickup($db, $user_id);
 		echo "<br /><br />
	 			<input type='checkbox' class='regular' name='consent' />
	   			".$text_field['student_consent']."
	 			<br /><br />
	   			<input type='submit' value='Submit Changes' />
	   	    	</form>
    		</article>
    	</div>";
 	}
 	
 	/* Displays selection area for which guardians can pick-up
 	 * unregistered student for student form in students.php */
 	private static function displayNewGuardianPickup($db, $user_id) {
 		echo "Which guardian/parent contacts are allowed to pick this
				student up for lunch or at the end of daily programs?";
 	
 		$query = "SELECT guardian_id FROM guardians
	    				WHERE user_id = :user_id";
 	
 		$query_params = array(':user_id' => $user_id);
 	
 		try {
 			$stmt = $db->prepare($query);
 			$result = $stmt->execute($query_params);
 		}
 		catch(PDOException $ex)  {
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
		   				</script>");
 		}
 		$rows = $stmt->fetchAll();
 	
 		if (empty($rows)) {
 			echo "<br /><span class='error'>No guardian/parent contacts registered.
					Please fill out the guardian and parent contact form whether or
					not student may leave on their own. </span>";
 		}
 		else {
 			echo "<ul>";
 			foreach ($rows as $row) {
 				$guardian = new Guardian($row['guardian_id'], $db);
 				echo "<li>
							<input class='regular' name='guardian_group[]'
								value='".$guardian->getId()."' type='checkbox'/>
							".$guardian->getName()."
							</li>";
 			}
 			echo "</ul>";
 		}
 	}
 	
 	/* Updates database record of student and returns true iff
 	 * update successful. */
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
			$stmt = $db->prepare ( $query );
			$result = $stmt->execute ( $query_params );
		} catch ( PDOException $ex ) {
			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
		}
		return True;
	}

}
   
