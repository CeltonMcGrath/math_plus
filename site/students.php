<?php  
    require("../library/common.php");     
    include '../library/config.php';
    include '../library/Form_Validator.php';
    include '../library/forms/html_Generator.php';
    include '../library/Student.php';
   
    $error = '';
    $success = '';
    
    $fg = new Form_Generator();
    $hg = new html_Generator();
    
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
    
?>


<!DOCTYPE html>
<html lang="en">
<?php include '../library/site_template/head_private_area.php' ?>
	<body>
		<?php include '../library/site_template/navbar.php' ?>   
		<div class="container">
			<h1>Students</h1>      
			<div class="accordion" id="accordion">
				<?php 
				$guardian_group = Student::getUnCheckedGuardianGroup($db, 
					$_SESSION['user']['user_id']);
				$id = '0';
				$label = 'Add new student';
				$article = $fg->studentForm(0, $preferred_name='', $birthdate='', 
					$gender='',  $grade ='', $allergies='', $medical='', 
					$perm_leave='', $perm_photo='', $perm_lunch='',
 					$cellphone='', $guardian_group);
 	
				echo $hg->bootstrapAccordion($id, $label, $article); 
				
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
					$studentForm = $student->displayStudentInfo();
					echo $hg->bootstrapAccordion($student->getId(), 
							$student->getName(), $studentForm);
				endforeach; ?>
			</div>	  		    
		</div>    
	</body>
</html>


