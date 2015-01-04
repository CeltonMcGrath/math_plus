<?php  
    require("../library/common.php");     
    include '../library/config.php';
    include '../library/Student.php';
   
    /* Add new student or update registered student.
     * Validate all user input. */
    if(!empty($_POST)) {
    	// Check for entered grade
    	if (sizeof(test_input($_POST['grade']))==0) {
    		$error = "Update unsuccesful. Please enter valid grade."
    	}
    	// Check user has not checked both leave permission boxes
    	elseif (isset($_POST['leave_permission']['leave_yes']) 
    			& isset($_POST['leave_permission']['leave_no'])) {
    		$error = "Update unsuccesful. Please indicate whether or not 
    				student may leave programs on their own.";
    	}
    	// Check user has checked at least one leave permission boxes
   		elseif (!isset($_POST['leave_permission']['leave_yes']) 
    			& !isset($_POST['leave_permission']['leave_no'])) {
    		$error = "Update unsuccessful. Please indicate whether or not 
    				student may leave programs on their own.";
    	}
    	elseif (!isset($_POST['consent'])) {
    		$error = "Update unsuccessful. Consent required to use this
    				registration system."
    	}
    	// If student id is 0, then user is inputting new student.
		elseif ($_POST['student_id']==0) {
    		// Check for non-empty first and last name
    		if (!count(test_input($_POST['first_name']) || 
    				!count(test_input($_POST['last_name']) {
    			$error = "Update unsuccessful. Please enter correct first name
    					 and last name.";
    		}
    		Student::createStudent($_SESSION['user']['user_id'],
    				$_POST['first_name'], $_POST['last_name'],
    				$_POST['preferred_name'], $_POST['grade'], 
    				$_POST['allergies'], $_POST['medical'], 
    				isset($_POST['leave_permission']['leave_yes']),
    				isset($_POST['photo_permission']), 
    				$_POST['guardian_group'], $db);
    		$success = "Update successful.";
    	}
    	// Otherwise, update student.
    	else {
    		Student::updateStudent($_POST['student_id'], 
    				$_POST['preferred_name'], $_POST['grade'], 
    				$_POST['allergies'], $_POST['medical'], 
    				isset($_POST['leave_permission']['leave_yes']),
    				isset($_POST['photo_permission']), 
    				$_POST['guardian_group'], $db);
    		$success = "Update successful."
    	}    
    }
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?>
	<section class="content">
		<h1>Students</h1>
			<span class='error'><?php $error?></span>
			<span class='success'><?php $success?></span>
			<section id="accordion">
				<?php Student::displayEmptyStudentForm($db, $_SESSION['user']['user_id']);
				// Generate accordian-style contact list
				
				// Query the db for student contacts associated with current user
				$query = "SELECT student_id FROM students 
						WHERE user_id = :user_id";
				
				$query_params = array (
						':user_id' => $_SESSION ['user']['user_id'] 
				);
				
				try {
					// Execute the query against the database
					$stmt = $db->prepare ( $query );
					$result = $stmt->execute ( $query_params );
				} catch ( PDOException $ex ) {
					die ( "Failed to run query: " . $ex->getMessage () );
				}
				$rows = $stmt->fetchAll();
				
				foreach ($rows as $row) :
					$student = new Student ($row['student_id'], $db);
					$student->displayStudentInfo();
				endforeach; ?>
			</section>
	</section>
<?php include '../library/site_template/footer.php';?>
</html>

