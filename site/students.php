<?php  
    require("../library/common.php");     
    include '../library/config.php';
    include '../library/Form_Validator.php';
    include '../library/Student.php';
   
    $error = '';
    $success = '';
    
    // Check if form has been submitted
    if(!empty($_POST)) {
	$form_validator = new Form_Validator();
    	$result = $form_validator->validateStudentPost($_POST);
    	if ($result != -1) {
    		$error = $result;
    	}
    	else {
    		$data = $form_validator->sanitizeStudentPost($_POST);
    		if ($data['student_id']==0) {
    			/* 0 indicates new student request. */
    			Student::createStudent(
    				$_SESSION['user']['user_id'], $data, $db);
    				$success = "Student successfully created.";
    		}
    		else {
    			/* Update student contact */
    			Student::updateStudent($_SESSION['user']['user_id'], $data, $db);
    				$success = "Student successfully updated.";
    		}
    	}
    }
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?>
	<section class="content">
		<span class="title"><h1>Students</h1></span>
			<span class='error'><?php echo $error ?></span>
			<span class='success'><?php echo $success ?></span>
			<section id="accordion">
				<?php Student::displayEmptyStudentForm
					($db, $_SESSION['user']['user_id']);
				
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
					error_log($ex->getMessage ());
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
