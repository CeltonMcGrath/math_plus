<?php
    // First we execute our common code to connection to the database and start the session 
    require("../common.php");   
    include 'user_registration/user_register.php';
    
    $successPhrase = "";
    $errorPhrase = "";
    
    if(!empty($_POST))
    {	
    	$email = $_POST['email'];
    	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		$errorPhrase = "Please enter a valid email.";
    	}
    	elseif (!userexists($email, $db)) {
    		$errorPhrase = "This email is not registered.";
    	}
    	else {
    		if (resetPassword($email, $db)) {
    			$successPhrase = "An email has been sent to your email address with a temporary
    					password. Please login with your temporary password and change it.
    					(Check your junk/ spam folders.)";
    			
    		}
    		else {
    			$errorPhrase = "Oops something went wrong! Please try again or contact 
    								system administrator. (See FAQ).";
    		}
    	}
    	
    }
?> 

<html>
	<head>
			<link rel="stylesheet" type="text/css" href="../css/login.css" />
	</head>
	<body>
		<section class="container">
			<div class="login">
				<form action="forgot_password.php" method="post"> 
				    Please enter your email:<br /> 
				    <input type="text" name="email" value="" />
				    <br /> 
				    <span class="success"><?php echo $successPhrase?></span>
					<span class="error"><?php echo $errorPhrase?></span>
				    <br /><br /> 
				    <input type="submit" value="Reset password" />
				</form>
			</div>
			<div class="login-extra">
				<a href="login.php">Return to login</a>
			</div>
		</section>
	</body>
</html>