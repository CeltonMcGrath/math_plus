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
		<div class="public-area">
			<div class="public-area-image-sigma">
				<img class='img-responsive' src='../resources/sigma.png'/>
			</div>
			<div class="public-area-image-banner">
				<img class='img-responsive' src='../public_html/math_webheader-1.png'/>
			</div>
			<div class="public-area-content">
				<h2>Please enter your email</h2>
				<?php 
					echo $hg->errorMessage($error);
					echo $hg->successMessage($success);		
				?>
				<form action="forgot_password.php" method="post"><fieldset>
					<div class='form-group'> 
						<label for="inputEmail" class="sr-only">Email address</label>
						<input type="email" id="inputEmail" 
							class="form-control input-lg" 
							placeholder="Email address" name='email'
							required autofocus>
					</div>
					<!-- Button -->
					<div class='form-group'>
						<button type='submit' class="btn btn-lg btn-primary btn-block">
							Reset my password
						</button>
					</div>
				</fieldset></form>
				<a href="login.php">Return to login</a>
			</div>	 
    	</div>
	</body>
</html>
