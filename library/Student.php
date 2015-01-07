<?php
include 'Program.php';
include 'Guardian.php';
include 'forms/Form_Generator.php';
include 'forms/html_Generator.php';

class Student {

	private $student_id; //integer
	private $user_id; //integer
	private $first_name; //string
	private $last_name; //string
	private $preferred_name; //string
	private $grade; //string
	private $allergies; //string
	private $medical; //string	
	private $permission_to_leave; //boolean
	private $photo_permission; //boolean
	private $database; //database connection
 
	/*---------------------------------------------------------------
	 *  Constructors
	 * ---------------------------------------------------------------*/
	
	/* Returns student object with student id s_id */
	public function __construct($s_id, $db) {  		
   		$this->student_id = $s_id;
   		$this->database = $db;
   		
   		/*Retrieves student data from db.*/
	   	$query = 'SELECT user_id, first_name, last_name, preferred_name, grade, 
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
	   	
		$this->user_id = $row['user_id'];
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
   		$perm_photo, $selected_guardians, $db) {	    
   		
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
	   	
	   	// Update guardian permissions for student
	    $student_id = $db->lastInsertId();	
	    self::updateGuardianGroup($selected_guardians, $student_id, $db);
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
		$fg = new Form_Generator();
		
		echo "
	    	<div class='contact'>
		   		<input class='accordion' type='checkbox'
		   			id=".$this->student_id." />
		   		<label for=".$this->student_id.">
		   			".$this->last_name.", ".$this->first_name."
		   		</label>
		   		<article>";
					echo $fg->studentForm($this->student_id, $this->preferred_name, 
						$this->grade, $this->allergies, $this->medical, 
						$this->photo_permission, $this->permission_to_leave, 
						$this->getGuardianGroup());
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
   
   	/* Returns array of guardian student relationship (all associated with
   	 * curret user session. 
  	 * $guardian_group is an array of 2-tuples:
	 * $guardian_id => Array($guardian_name, $checked)
	 * $checked is a boolean indicating whether or not to check the checkbox.
	 * (ie. Used to show that guardian can pick up student.) */
	private function getGuardianGroup() {
		// Retrieve all guardians associated with user.
 		$guardian_group = self::getUncheckedGuardianGroup($this->database, $this->user_id);
 		
		// Select guardians which are already allowed to pick-up student
 		$query = "SELECT guardian_id FROM students_guardians
	    			WHERE student_id = :student_id";
 			
 		$query_params = array(':student_id' => $this->student_id);
 			
 		try {
 			$stmt = $this->database->prepare($query);
 			$result = $stmt->execute($query_params);
 		}
 		catch(PDOException $ex)  {
 			echo("<script>console.log('PHP: ".$ex->getMessage()."');
		   		</script>");
 		} 			
 		$rows = $stmt->fetchAll();
 	
 			// Create array of guardian_id's
 			$certain_guardians = array();
 			foreach ($rows as $row) {
 				$certain_guardians[$row['guardian_id']] = true;
 			}
 						
 		foreach ($guardian_group as $guardian_id=>$tuple) {
 			if (isset($certain_guardians[$guardian_id])) {
 				$guardian_group[$guardian_id][1] = true;
 			}			
 		}
 		
 		return $guardian_group;
 	}
	  
 	/* Displays empty student form. */
 	public static function displayEmptyStudentForm($db, $user_id) {
 		$guardian_group = self::getUnCheckedGuardianGroup($db, $user_id);

 		$fg = new Form_Generator();
		$hg = new html_Generator();
		
		$id = '0';
		$label = 'Add new student';
		$article = $fg->studentForm(0, $preferred_name='', $grade ='',
 				$allergies='', $medical='', $photo_permission=false,
 				$leave_permission=false, $guardian_group);
 		echo $hg->accordionBox($id, $label, $article); 
 	}
 	
 	/* Retrieves guardians associated with this user.
 	 * Returns an array who's values are guadian ids.*/
 	 private static function getUnCheckedGuardianGroup($db, $user_id) {
 	 	// Get all guardians associated with user
 	 	$query = "SELECT guardian_id FROM guardians
	    		WHERE user_id = :user_id";
 	
 	 		$query_params = array(':user_id' => $user_id);
 	
 	 		try {
 	 		$stmt = $db->prepare($query);
 	 		$result = $stmt->execute($query_params);
 	 		}
 	 		catch(PDOException $ex)  {
 	 		error_log($ex->getMessage());
 	 		}
 	 		$rows_guardians = $stmt->fetchAll();
 	
 	 		// Create array of guardian_id's
 	 		$guardian_group = array();
 	 		foreach ($rows_guardians as $row) {
 	 			$guardian = new Guardian($row['guardian_id'], $db);
 	 			$guardian_group[$guardian->getId()] = 
 	 				array($guardian->getName(), false);
 	 		}
		return $guardian_group;
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
 				(".$row['start_date']."-".$row['end_date'].")
 				(<i>Registered</i>)</li>";
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
 				(".$row['start_date']."-".$row['end_date'].")
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
 		
 	/* Updates database records related to student and returns true 
 	 * iff update successful. */
	public static function updateStudent($s_id, $p_name, 
    				$gr, $all, $med, $leave_perm, $photo_perm, 
		$selected_guardians, $db) {
		//Update students table
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
		//Update guardian permissions
		self::updateGuardianGroup($selected_guardians, $s_id, $db);
		
		return True;
	}
	
	/* Updates guardian permissions */
	public static function updateGuardianGroup($selected_guardians, $student_id, 
			$db) {
		//Delete old students guardian permissions
		$query = "DELETE FROM students_guardians
	    		WHERE student_id = :student_id";
		 
		$query_params = array(':student_id' => $student_id);
		 
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex)	{
			echo("<script>console.log('PHP: DELETE".$ex->getMessage()."');
	   				</script>");
		}
		echo("<script>console.log('PHP: DELETE')</script>");;
		// Add new student guardian permissions for each guardian
		foreach ($selected_guardians as $guardian_id) {	
			$query = "INSERT INTO students_guardians (student_id, guardian_id)
		   			VALUES (:student_id, :guardian_id)";
				
			$query_params = array(
					':student_id' => $student_id,
					':guardian_id' => $guardian_id,
			);
			
			try	{
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				echo("<script>console.log('PHP: REPLACE ".$student_id."
				 	".$guardian_id." ".$ex->getMessage()." ');
		   				</script>");
			}
		}	
	}
			

}
   
