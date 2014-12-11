<?php
	// This if statement checks to determine whether the add new guardian form as been submitted.
    if(!empty($_POST))
    {
    	  	
    	//     	//Validate user input
    	//     	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    		//     	{
    	//     		$emailErr = "Valid email is required";
    	//     		$valid_id = false;
    	//     	}
    	//     	else {
    	//     		$email  = $_POST['email'];
    	//     	}
    	   
    	if ($_POST['student_id']==0) {
    		$user_id = $_SESSION['user']['user_id'];
    		$first_name = $_POST['first_name'];
    		$last_name = $_POST['last_name'];
    		$preferred_name = $_POST['preferred_name'];
    		$grade = $_POST['grade'];
    		$allergies = $_POST['allergies'];
    		$medical = $_POST['medical'];
    		
    		$query = "INSERT INTO students (user_id, first_name, last_name, preferred_name, grade, allergies, medical) VALUES
				(:user_id, :first_name, :last_name, :preferred_name, :grade, :allergies, :medical)";
    		
    		$query_params = array(
    				':user_id' => $user_id,
    				':first_name' => $first_name,
    				':last_name' => $last_name,
    				':preferred_name' => $preferred_name,
    				':grade' => $grade,
    				':allergies' => $allergies,
    				':medical' => $medical
    		);
    	}
    	else {
    		$student_id = $_POST['student_id'];
    		if(isset($_POST['delete'])) {
    			$query = "DELETE FROM students
    				  WHERE student_id = :student_id";
    			 
    			$query_params = array(
    					':student_id' => $student_id
    			);
    		}
    		else {
	    		$preferred_name = $_POST['preferred_name'];
	    		$grade = $_POST['grade'];
	    		$allergies = $_POST['allergies'];
	    		$medical = $_POST['medical'];
    			 
    			$query = "UPDATE students
    				  SET preferred_name = :preferred_name, grade = :grade, allergies = :allergies, medical = :medical
    				  WHERE student_id = :student_id";
    			 
    			$query_params = array(
    					':preferred_name' => $preferred_name,
	    				':grade' => $grade,
	    				':allergies' => $allergies,
	    				':medical' => $medical,
    					':student_id' => $student_id
    			);
    		}
    	}
			 
    	try
    	{
    		// Execute the query against the database
    		$stmt = $db->prepare($query);
    		$result = $stmt->execute($query_params);
    	}
    	catch(PDOException $ex)
    	{
    	// Note: On a production website, you should not output $ex->getMessage().
    	// It may provide an attacker with helpful information about your code.
    		die("Failed to run query: " . $ex->getMessage());
    	}
    	 
    }
?>