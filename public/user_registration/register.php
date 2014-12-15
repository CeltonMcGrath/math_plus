<?php
    // First we execute our common code to connection to the database and start the session 
    require("../../common.php");   
    include 'user_register.php';

    // This if statement checks to determine whether the registration form has been submitted 
    // If it has, then the registration code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
    	$valid_id = true; 
    	 
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
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            $passwordErr = "Password is required"; 
            $valid_id = false;
        } 
        
        // Ensure that the user has entered the second password correctly
        if($_POST['password'] != $_POST['password2'])
        {
        	$password2Err = "Passwords do not match.";
        	$valid_id = false;
        }
        
        // If user already exists, display error. Otherwise, register user.
        if($valid_id)
        {
        	if (userExists($_POST['email'], $db))
        	{
        		$errorPhrase = "This email is already in use.";
        	}
        	else
        	{
        		if (addUser($_POST['email'], $_POST['password'], $db))
        		{
        			$successPhrase = "Registration a success. An activation link has been sent to your email.
        					 You must activitate your account via this link. Please check your spam/junk folders.";
        		}
        		else
        		{
        			$errorPhrase = "Registration failed.";
        		}
        		
        	}
        }	 
        else 
        {
        	$emailEntry = $_POST['email'];
        }
       
    } 
    else {
    	$emailEntry = $emailErr = $email2Err = $passwordErr = $password2Err = "";
    	$successPhrase = "";
    	$errorPhrase = "";
    }   
?> 

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/login.css" />
	</head>
	<body>
		<section class="container">
			<div class="login">
				<h1>Register</h1> 
				<br />
				<form action="register.php" method="post"> 
					<span class="success"><?php echo $successPhrase?></span>
					<br><br />
					<span class="error">*Required fields</span>
					<span class="error"><?php $errorPhrase?></span>
					<br><br />				
				    Email:<br /> 
				    <input type="text" name="email" value="<?php echo $emailEntry;?>" /> 
				    <span class="error">* <?php echo $emailErr;?></span>
				    <br /><br /> 
				    Re-enter your email:<br /> 
				    <input type="text" name="email2" value="" /> 
				    <span class="error">* <?php echo $email2Err;?></span>
				    <br /><br /> 
				    Password:<br /> 
				    <input type="password" name="password" value="" /> 
				    <span class="error">* <?php echo $passwordErr;?></span>
				    <br /><br /> 
				    Re-enter password:<br /> 
				    <input type="password" name="password2" value="" /> 
				    <span class="error">* <?php echo $password2Err;?></span>
				    <br /><br />
				    <input type="submit" value="Register" /> 
				</form>				
			</div>
			<div class="login-extra">
				<a href="../login.php">Return to login</a>
			</div>
		</section>
	</body>
</html>