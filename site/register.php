<?php
    // First we execute our common code to connection to the database and start the session 
    require("../library/common.php");   
    include '../library/user_registration/user_register.php';
    include '../library/Form_Validator.php';
	include '../library/forms/Form_Generator.php';
	
	$fg = new Form_Generator();
	
    $error = '';
    $success = '';
    
    // Check if form has been submitted
    if(!empty($_POST)) {
    	$form_validator = new Form_Validator();
    	$result = $form_validator->validateRegistrationPost($_POST);
    	if ($result != -1) {
    		$error = $result;
    	}
	else {
		$data = $form_validator->sanitizeRegistrationPost($_POST);
		// Check for uniqueness of email
		if (userExists($data['email'], $db)) {
			$error = "This email is already in use.";
		}
        else {
        	if (addUser($data['email'], $data['password'], $data['listserv'], 
        			$db)) {
        		$success = "Registration a success. 
        				An activation link has been sent to your email.
        				 You must activitate your account via this link. 
        				Please check your spam/junk folders.";
        	}
        	else {
        		$error = "Registration failed. 
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
		<!-- jQuery -->
        <script src="http://code.jquery.com/jquery-latest.js"></script>
		 <!-- Parsley -->
        <script type="text/javascript" src="../public_html/parsley.js"></script>
		<link rel="icon" href="../public_html/favicon.ico" type="image/x-icon">
	</head>
	
	<section class="content">
		<section class="container">
			<div class="login">
				<h1>Register</h1> 
				<span class="error"><?php echo $error?></span>
				<span class="success"><?php echo $success?></span>
				<br />
				<?php echo $fg->registrationForm(); ?>		
			</div>
			<div class="login-extra">
				<a href="login.php">Return to login</a>
			</div>
		</section>
	</section>
	
	<script type="text/javascript">
  		$('#form').parsley();
	</script>
</html>
