<?php
include 'Program.php';
include 'Guardian.php';
include 'forms/Form_Generator.php';

class Student {

	private $student_id; //integer, required
	private $user_id; //integer, required
	private $first_name; //string, required
	private $last_name; //string, required
	private $preferred_name; //string, optional
	private $birthdate; //string (yyyy-mm-dd), required
	private $gender; //string, 'boy' or 'girl', optional
	private $grade; //string, required
	private $allergies; //string
	private $medical; //string	
	private $perm_leave; //0 - no (must be picked up), 1-yes
	private $perm_lunch; //0 - no, 1-yes, 2-pick up for lunch
	private $perm_photo; //0 - no, 1-yes&age18, 2-yes&guardianconsent
	private $cellphone; //string, 
	private $database; //database connection
 
	/*---------------------------------------------------------------
	 *  Constructors
	 * ---------------------------------------------------------------*/
	
	/* Returns student object with student id s_id */
	public function __construct($s_id, $db) {  		
   		$this->student_id = $s_id;
   		$this->database = $db;
   		
   		/*Retrieves student data from db.*/
	   	$query = 'SELECT user_id, first_name, last_name, preferred_name, 
	   			birthdate, gender, grade, allergies, medical, perm_leave, 
	   			perm_lunch, perm_photo, cellphone
	   			FROM students
	    		WHERE student_id = :student_id';
	   	
	   	$query_params = array(':student_id' => $this->student_id);
	   	 
	   	try {
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		error_log($ex->getMessage());
	   	}
	   	$row = $stmt->fetch();
	   	
		$this->user_id = $row['user_id'];
	   	$this->first_name = $row['first_name']; 
	   	$this->last_name = $row['last_name']; 
	   	$this->preferred_name = $row['preferred_name']; 
	   	$this->birthdate = $row['birthdate'];
	   	$this->gender = $row['gender'];
	   	$this->grade = $row['grade']; 
	   	$this->allergies = $row['allergies']; 
	   	$this->medical = $row['medical'];
	   	$this->perm_leave = $row['perm_leave'];
	   	$this->perm_lunch = $row['perm_lunch'];
	   	$this->perm_photo = $row['perm_photo'];
	   	$this->cellphone = $row['cellphone'];
   }

	/*Creates student contact in database.*/
	public static function createStudent($u_id, $data, $db) {	    
   		
		$query = "INSERT INTO students (user_id, first_name, last_name, 
				preferred_name, birthdate, gender, grade, allergies, medical, 
				perm_leave, perm_lunch, perm_photo, cellphone) 
	   			VALUES
				(:user_id, :first_name, :last_name, :preferred_name, 
	   			:birthdate, :gender, :grade, :allergies, :medical, :perm_leave, 
	   			:perm_lunch, :perm_photo, :cellphone)";
	   	 
		$query_params = array(
	   			':user_id' => $u_id,
	   			':first_name' => $data['first_name'],
	   			':last_name' => $data['last_name'],
	   			':preferred_name' => $data['preferred_name'],
				':gender' => $data['gender'],
				':birthdate' => $data['birthdate'],
	   			':grade' => $data['grade'],
	   			':allergies' => $data['allergies'],
	   			':medical' => $data['medical'],
	   			':perm_leave' => $data['perm_leave'],
				':perm_lunch' => $data['perm_lunch'],
	   			':perm_photo' => $data['perm_photo'],
				':cellphone' => $data['cellphone']
	   	);
	   	
	   	try	{
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		error_log($ex->getMessage());
	   	}
	   	
	   	// Update guardian permissions for student
	    $student_id = $db->lastInsertId();	
	    self::updateGuardianGroup($data['guardian_group'], $student_id, $db);
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
		
		$form = $fg->studentForm($this->student_id, 
						$this->preferred_name, $this->birthdate, $this->gender, 
						$this->grade, $this->allergies, $this->medical, 
						$this->perm_leave, $this->perm_lunch,
						$this->perm_photo, $this->cellphone,
						$this->getGuardianGroup());
		$form .= "<br />";
		$form .= $this->displayFutureProgramList();
		$form .= "<br />";
		$form .= $this->displayPastProgramList();
		$form .= "<form class='form-horizontal' action='programs.php' 
						method='post'>
					<fieldset>
 						<input type='hidden' name='student_id' 
 							value=".$this->student_id."/>
 						<div class='col-md-4'>
						</div>	
						<div class='col-md-4'>
	 						<button type='submit' 
	 							class='btn btn-lg btn-primary btn-block' >
			    				Add or view programs
			    			</button>
	 					</div>
	 					</fieldset>
 					</form>";
 		return $form;		
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
 			error_log($ex->getMessage());
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
	  
 	/* Retrieves guardians associated with this user.
 	 * Returns an array who's values are guadian ids.*/
 	 public static function getUnCheckedGuardianGroup($db, $user_id) {
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
 		$form = "Upcoming programs: <ul>";
 		
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
 			error_log($ex->getMessage());
 		}
 		$rows = $stmt->fetchAll();
 		 		
 		foreach($rows as $row):
 			$form .= "<li>".$row['program_name']." 
 				(".$row['start_date']."-".$row['end_date'].")
 				(<i>Registered</i>)</li>";
 		endforeach;
 		$form .= "</ul>";
 		return $form;
 	}  
 	
 	/* Print a html list of programs this student is enrolled in that
 	 * ended in the past. */
 	public function displayPastProgramList() {
 		$form = "Past programs: <ul>";
 		
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
 			error_log($ex->getMessage());
 		}
 		$rows = $stmt->fetchAll();
 			
 		foreach($rows as $row):
 			$form .= "<li>".$row['program_name']." 
 				(".$row['start_date']."-".$row['end_date'].")
 				</li>";
 		endforeach;
 		$form = "</ul>";
 		return $form;	
 	}
 	
 	/*---------------------------------------------------------------
 	 *  Functions which are used for programs.php
 	 *  (Individualized display for each student.)
 	 * ---------------------------------------------------------------*/
 	
 	// Returns true iff student is registered in program.
 	public function inProgram($program_id) {
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
 			error_log($ex->getMessage());
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
	public static function updateStudent($s_id, $data, $db) {
		//Update students table
		$query = "UPDATE students
	    		SET preferred_name = :preferred_name, 
					birthdate = :birthdate,
					gender = :gender,
					grade = :grade, 
					allergies = :allergies, 
					medical = :medical,
					perm_photo = :perm_photo,
					perm_leave = :perm_leave,
					perm_lunch = :perm_lunch,
					cellphone = :cellphone
	    		WHERE student_id = :student_id";
		
		$query_params = array (
				':preferred_name' => $data['preferred_name'],
				':birthdate' => $data['birthdate'],
				':grade' => $data['grade'],
				':gender' => $data['gender'],
				':allergies' => $data['allergies'],
				':medical' => $data['medical'],
				':perm_photo' => $data['perm_photo'],
				':perm_leave' => $data['perm_leave'],
				':perm_lunch' => $data['perm_lunch'],
				':cellphone' => $data['cellphone'],
				':student_id' => $data['student_id']
		);
		
		try {
			$stmt = $db->prepare ( $query );
			$result = $stmt->execute ( $query_params );
		} catch ( PDOException $ex ) {
			error_log($ex->getMessage());
		}
		//Update guardian permissions
		self::updateGuardianGroup($data['guardian_group'], $s_id, $db);
		
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
			error_log($ex->getMessage());
		}

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
				error_log($ex->getMessage());
			}
		}	
	}
			

}
   
