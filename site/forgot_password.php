<?php
    // First we execute our common code to connection to the database and start the session 
    require("../library/common.php");   
    include '../library/User.php';
    
    $success = "";
    $error = "";
    
    if(!empty($_POST)) {	
    	$email = $_POST['email'];
    	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		$error = "Please enter a valid email.";
    	}
    	elseif (!User::userexists($email, $db)) {
    		$error = "This email is not registered.";
    	}
    	else {
    		$user = new User($email, $db);
    		$user->resetPassword();
    		$success = "An email has been sent to your email address 
    					with a temporarypassword. Please login with your 
    					temporary password and change it.
    					(Check your junk/ spam folders.)";
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
	<body>
		<section class="container">
			<div class="login">
				<form action="forgot_password.php" method="post"> 
				    Please enter your email:
				    <input type="text" name="email" value="" />
				    <br /> 
				    <span class="success"><?php echo $success ?></span>
					<span class="error"><?php echo $error ?></span>
				    <br />
				    <input type="submit" value="Reset password" />
				</form>
			</div>
			<div class="login-extra">
				<a href="login.php">Return to login</a>
			</div>
		</section>
	</body>
</html>
