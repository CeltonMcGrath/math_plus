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
   		if ($this->photo_permission) {
   			$photo_check = 'checked';
   		}

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
 		
 		//Select programs student is already registered in
 		$query = 'SELECT program_id, status
	   			FROM
 					students_programs 
	   			WHERE
 					students_programs.student_id = :student_id';
 		$query_params = array(':student_id' => $this->student_id);   	
	   	try	{
	   		// Execute the query against the database
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	} catch(PDOException $ex) {
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	$studentPrograms = $stmt->fetchAll();
 				
 		foreach($programRows as $programRow):
 			$program_id = $programRow['program_id'];
 			echo "	
 			<div class='contact'>
 				<input class='accordion' type='checkbox' 
 					id='".$program_id."'/>";
 				if ($this->studentInProgram($program_id, $studentPrograms)) {
 					echo "<label for='".$program_id."'>
 					".$programRow['program_name']." (Status: ".$status.")
 					</label>";
 				}
 				else {
 					echo "<label for='".$program_id."'>
 						<input class='regular' name='program_group[]' 
 							value='".$program_id."' type='checkbox'/>
 						".$programRow['program_name'].",
 								 (".$this->remainingSpots($program_id)."
 						spots remaining) Fee: ".$programRow['cost']."
 					</label>";
 				}
 			echo "<article>
 					<ul>
 						<li>Start date: ".$programRow['start_date']."</li>
 						<li>End date: ".$programRow['end_date']."</li>
 						<li>Registration deadline: 
 								".$programRow['registration_deadline']."
 						</li>
 						<li>Grade levels: ".$programRow['grades']."</li>
 						<li>Description: ".$programRow['description']."</li>
 					</ul>
 				</article>
 			</div>";
 		endforeach;
 		
 	}
 	
 	private function studentInProgram($program_id, $studentsRows) {
 		return False;
 	}
 	
 	private function remainingSpots($program_id) {
 		return 0;
 	}
 	
	public static function updateStudent($s_id, $p_name, 
    				$gr, $all, $med, $leave_perm, $photo_perm, $db) {
		/* Updates student data and returns true iff success. */
		
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
   
   public function programCartDisplay() {
	   	/*$product_code = $cart_itm["code"];
	   	$results = $mysqli->query("SELECT product_name,product_desc, price FROM products WHERE product_code='$product_code' LIMIT 1");
	   	$obj = $results->fetch_object();
	   	
	   	echo '<li class="cart-itm">';
	   	echo '<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["code"].'&return_url='.$current_url.'">&times;</a></span>';
	   	echo '<div class="p-price">'.$currency.$obj->price.'</div>';
	   	echo '<div class="product-info">';
	   	echo '<h3>'.$obj->product_name.' (Code :'.$product_code.')</h3> ';
	   	echo '<div class="p-qty">Qty : '.$cart_itm["qty"].'</div>';
	   	echo '<div>'.$obj->product_desc.'</div>';
	   	echo '</div>';
	   	echo '</li>';
	   	$subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
	   	$total = ($total + $subtotal);
	   	
	   	echo '<input type="hidden" name="item_name['.$cart_items.']" value="'.$obj->product_name.'" />';
	   	echo '<input type="hidden" name="item_code['.$cart_items.']" value="'.$product_code.'" />';
	   	echo '<input type="hidden" name="item_desc['.$cart_items.']" value="'.$obj->product_desc.'" />';
	   	echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'" />';
	   	$cart_items ++;*/
   }

}
   
