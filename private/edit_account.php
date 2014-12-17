<?php 
    require("../common.php"); 
    include '../template/header.php';
    include '../public/user_registration/user_register.php';
    
    $success = "";
    $error = "";
    
    // This if statement checks to determine whether the edit form has been submitted 
    if(!empty($_POST)) { 
    	if ($_POST['update']=='email') {
    		/* Update user's email */

    		// Check for valid email
    		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    			$error = "Update unsuccessful: please enter valid email.";
    		} 
    		elseif ($_POST['email']!=$_POST['email2']) {
    			$error = "Update unsuccessful: emails do not match.";
    		}
    		elseif (userexists($_POST['email'])) {
    			$error = "Update unsuccessful: user already exists in system.";
    		} 
    		else {
    			if (True) {
    				$success = "Update successful. An activation link has been 
    						sent to your email. Your account will be inactive
    						until your activate via this link. 
    						(Please check your junk/spam email folders.)";
    			}
    			else {
    				$error = "Update unsuccessful. Please try again or contact
    						the system administrator.";
    			}
    		}
    		// Make sure the user entered a valid E-Mail address
    		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    		{
    			$emailErr = "Valid email is required";
    			$valid_id = false;
    		}
    		
    		//Ensure that the user has entered matching email
    		if ($_POST['email'] != $_POST['email2'])
    		{
    			$email2Err = "Email does not match.";
    			$valid_id = false;
    		}
    		
    	}
    	else {
    		/* Update user's password */
    	}
    }
	/* Save this code for later. 
        
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] != $_SESSION['user']['email']) 
        { 
            // Define our SQL query 
            $query = " 
                SELECT 
                    1 
                FROM users 
                WHERE 
                    email = :email 
            "; 
             
            // Define our query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'] 
            ); 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                die("Failed to run query: " . $ex->getMessage()); 
            } 
             
            // Retrieve results (if any) 
            $row = $stmt->fetch(); 
            if($row) 
            { 
                die("This E-Mail address is already in use"); 
            } 
        } 
        
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
            $password = hash('sha256', $_POST['password'] . $salt); 
            for($round = 0; $round < 65536; $round++) 
            { 
                $password = hash('sha256', $password . $salt); 
            } 
        } 
        else 
        { 
            // If the user did not enter a new password we will not update their old one. 
            $password = null; 
            $salt = null; 
        } 
         
        // Initial query parameter values 
        $query_params = array( 
            ':email' => $_POST['email'], 
            ':user_id' => $_SESSION['user']['id'], 
        ); 
         
        // If the user is changing their password, then we need parameter values 
        // for the new password hash and salt too. 
        if($password !== null) 
        { 
            $query_params[':password'] = $password; 
            $query_params[':salt'] = $salt; 
        } 
         
        // Note how this is only first half of the necessary update query.  We will dynamically 
        // construct the rest of it depending on whether or not the user is changing 
        // their password. 
        $query = " 
            UPDATE users 
            SET 
                email = :email 
        "; 
         
        // If the user is changing their password, then we extend the SQL query 
        // to include the password and salt columns and parameter tokens too. 
        if($password !== null) 
        { 
            $query .= " 
                , password = :password 
                , salt = :salt 
            "; 
        } 
         
        // Finally we finish the update query by specifying that we only wish 
        // to update the one record with for the current user. 
        $query .= " 
            WHERE 
                id = :user_id 
        "; 
         
        try 
        { 
            // Execute the query 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
        // array is stale; we need to update it so that it is accurate. 
        $_SESSION['user']['email'] = $_POST['email']; 
         
        // This redirects the user back to the members-only page after they register 
        header("Location: splash.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to splash.php"); 
    } */     
?> 

<html>
	<body>
		<h1>Edit Account</h1> 
		
		<h2>Update email address</h2>
		<span class="success"><?php echo $success?></span>
		<span class="error"><?php echo $error?></span>  
		<form action="edit_account.php" method="post"> 
			<input type="hidden" name="update" value="email" />
		    Current email: <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>
		    <br /> <br />  
		    New email:<br /> 
		    <input type="text" name="email" value="" />
		    <br /><br /> 
		    Re-enter new email:<br /> 
		    <input type="text" name="email2" value="" />
		    <br /><br /> 
		    <input type="submit" value="Update email" /> 
		</form>
		
		<h2>Update password</h2>
		<span class="success"><?php echo $success?></span>
		<span class="error"><?php echo $error?></span>
		<form action="edit_account.php" method="post"> 
			<input type="hidden" name="update" value="password" />
		    Current password:<br /> 
		    <input type="password" name="password" value="" />
		    <br /><br /> 
		    New Password:<br /> 
		    <input type="password" name="password" value="" /> 
			<br /><br /> 
		    Re-enter new Password:<br /> 
		    <input type="password" name="password" value="" />
		    <br /><br /> 
		    <input type="submit" value="Update password" /> 
		</form>
	</body>
</html>