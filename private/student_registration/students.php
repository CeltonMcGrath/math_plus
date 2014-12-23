<?php  
    require("../../common.php");     
    include 'Student.php';
    include '../../template/head.php';
    include '../../template/header.php';
    
    // This if statement checks to determine whether the add new student form as been submitted.
    if(!empty($_POST)) {
    	if ($_POST['student_id']==0) {
    		Student::createStudent($_SESSION['user']['user_id'],
    				$_POST['first_name'], $_POST['last_name'],
    				$_POST['preferred_name'], $_POST['grade'], $_POST['allergies'],
    				$_POST['medical'], $_POST['permission_to_leave'],
    				$_POST['photo_permission'], $db);
    	}
    	else {
    		$student_id = $_POST['student_id'];
    		if($_POST['delete'] != 'no') {
    			Student::deleteStudent($student_id, $db);
    		}
    		else {
    			Student::updateStudent($student_id, $_POST['preferred_name'], 
    				$_POST['grade'], $_POST['allergies'],
    				$_POST['medical'], $_POST['permission_to_leave'],
    				$_POST['photo_permission'], $db);
    		}
    	}    
    }
?>
	<section class="content">
		<h1>Students</h1>
			<section id="accordion">
				<?php Student::displayEmptyStudentForm ();
				// Generate accordian-style contact list
				
				// Query the db for guardian contacts associated with current user
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
				$rows = $stmt->fetchAll ();
				
				foreach ( $rows as $row ) :
					$student = new Student ($row ['student_id'], $db );
					$student->displayStudentInfo();
				endforeach; ?>
			</section>
	</section>
<?php include '../../template/footer.php';?>
</html>

