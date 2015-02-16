<?php
    // First we execute our common code to connection to the database and start the session 
    require("../library/common.php");   
    include '../library/User.php';
    include '../library/forms/html_Generator.php';
    
    $hg = new html_Generator();
    
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
    					with a temporary password. Please login with your 
    					new password. You can change it once you login.
    					(Check your junk/ spam folders.)";
    	}
    }
?> 

<html>
	<?php include '../library/site_template/head_public_area.php'?>
	<body>
		<div class="container">
			<form class="form-signin" action="forgot_password.php" 
				method="post" >
			    <h2 class="form-signin-heading">Please enter your email</h2>
			    <?php 
					echo $hg->errorMessage($error);
					echo $hg->successMessage($success);		
				?>
			    <label for="inputEmail" class="sr-only">Email address</label>
			        <input type="email" id="inputEmail" class="form-control" 
			        	placeholder="Email address" name='email'
			        	required autofocus>
			    <button class="btn btn-lg btn-primary btn-block" 
			        	type="submit">Reset my password</button>
			     <a href="login.php">Return to login</a>
			 </form>
		</div>
	</body>
</html>
