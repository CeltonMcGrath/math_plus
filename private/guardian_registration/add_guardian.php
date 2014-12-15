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
    	   
    	if ($_POST['guardian_id']==0) {
    		$user_id = $_SESSION['user']['user_id'];
    		$first_name = $_POST['first_name'];
    		$last_name = $_POST['last_name'];
    		$phone_1 = $_POST['phone_1'];
    		$phone_2 = $_POST['phone_2'];
    		$email = $_POST['email'];
    		
    		$query = "INSERT INTO guardians (user_id, first_name, last_name, phone_1, phone_2, email) VALUES
				(:user_id, :first_name, :last_name, :phone_1, :phone_2, :email)";
    		
    		$query_params = array(
    				':user_id' => $user_id,
    				':first_name' => $first_name,
    				':last_name' => $last_name,
    				':phone_1' => $phone_1,
    				':phone_2' => $phone_2,
    				':email' => $email
    		);
    	}
    	else {
    		$guardian_id = $_POST['guardian_id'];
    		if(isset($_POST['delete'])) {
    			$query = "DELETE FROM guardians
    				  WHERE guardian_id = :guardian_id";
    			 
    			$query_params = array(
    					':guardian_id' => $guardian_id
    			);
    		}
    		else {
    			$phone_1 = $_POST['phone_1'];
    			$phone_2 = $_POST['phone_2'];
    			$email = $_POST['email'];
    			 
    			$query = "UPDATE guardians
    				  SET phone_1 = :phone_1, phone_2 = :phone_2, email = :email
    				  WHERE guardian_id = :guardian_id";
    			 
    			$query_params = array(
    					':phone_1' => $phone_1,
    					':phone_2' => $phone_2,
    					':email' => $email,
    					':guardian_id' => $guardian_id
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