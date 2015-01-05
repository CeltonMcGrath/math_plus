<?php
    // First we execute our common code to connection to the database and start the session 
    require("../library/common.php");   
    include '../library/user_registration/user_register.php';
    include '../library/Form_Validator.php';

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
        	if (addUser(data['email'], data['password'], data['listserv'], 
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
				<form action="register.php" method="post" 
					id='form' data-validate="parsley"> 
					<span class="error">*Required fields</span>
					<br><br />				
				    Email:<br /> 
				    <input type="email" name="email" id="email"
				    	data-parsley-trigger="change" required /> 
				    <br /><br /> 
				    Re-enter your email:<br /> 
				    <input type="email" name="email2"
				    	data-parsley-trigger="change" required   
				    	data-parsley-equalto="#email1"/> 
				    <br /><br /> 
				    Password:<br /> 
				    <input type="password" name="password" id="password"
				    	pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,8}$" 
				    	required 
				    	data-required-message="Password must be at least 4 
				    		characters, no more than 8 characters, and 
				    		must include at least one upper case letter, 
				    		one lower case letter, and one numeric digit." 
				    /> 
				    <br /><br /> 
				    Re-enter password:<br /> 
				    <input type="password" name="password2" 
				    	data-parsley-trigger="change" required   
				    	data-parsley-equalto="#password" /> 
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
	
	<script type="text/javascript">
  		$('#form').parsley();
	</script>
</html>