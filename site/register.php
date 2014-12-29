<?php
    // First we execute our common code to connection to the database and start the session 
    require("../library/common.php");   
    include '../library/user_registration/user_register.php';

    $emailEntry = $emailErr = $email2Err = $passwordErr = $password2Err = "";
    $successPhrase = "";
    $errorPhrase = "";
    
    // Check if the form has been submitted. If so, attempt to register the user.
    if(!empty($_POST)) { 
    	$emailEntry = $_POST['email'];
    	
        // Check for valid e-mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { 
            $emailErr = "Valid email is required";
        } 
        // Check for matching e-mail address
        elseif ($_POST['email'] != $_POST['email2']) {
        	$email2Err = "Email does not match.";
        }
        // Check for non-empty password
        elseif(empty($_POST['password']))  { 
            $passwordErr = "Password is required."; 
        } 
        // Check for valid password
        elseif (!validPassword($_POST['password'])) {
        	$passwordErr = "Pass must contain at least 8 characters, contain 
        			only letters, numbers or ...";
        }
        // Check for matching password
        elseif($_POST['password'] != $_POST['password2']) {
        	$password2Err = "Passwords do not match.";
        }
        // Check for uniqueness of email 
        elseif (userExists($_POST['email'], $db)) {
        	$errorPhrase = "This email is already in use.";
        }
        else {
        	if (addUser($_POST['email'], $_POST['password'], 
        			isset($_POST['listserv']), $db)) {
        		$successPhrase = "Registration a success. 
        				An activation link has been sent to your email.
        				 You must activitate your account via this link. 
        				Please check your spam/junk folders.";
        	}
        	else {
        		$errorPhrase = "Registration failed. 
        				Please try again or contact administrator.";
        	}
        }	 
    }  
?> 

<html>
	<head>
		<meta charset="utf-8">
		<title>Math+ Registration</title>
		<style type="text/css">
			@import url(../public_html/style.css);
			@import url(../public_html/main.css);
		</style>
		<link rel="icon" href="../public_html/favicon.ico" type="image/x-icon">
	</head>
	
	<section class="content">
		<section class="container">
			<div class="login">
				<h1>Register</h1> 
				<br />
				<form action="register.php" method="post"> 
					<span class="success"><?php echo $successPhrase?></span>
					<br><br />
					<span class="error">*Required fields</span>
					<span class="error"><?php echo $errorPhrase?></span>
					<br><br />				
				    Email:<br /> 
				    <input type="email" name="email" value="<?php echo $emailEntry;?>" /> 
				    <span class="error">* <?php echo $emailErr;?></span>
				    <br /><br /> 
				    Re-enter your email:<br /> 
				    <input type="email" name="email2" value="" /> 
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
				    Would you like receive email notifications about upcoming
				    programs?
				    <br />
				    <input type="checkbox" class="regular" 
				    	name="listserv" checked>Yes
				    <br /><br />
				    <input type="submit" value="Register" /> 
				</form>				
			</div>
			<div class="login-extra">
				<a href="login.php">Return to login</a>
			</div>
		</section>
	</section>
</html>