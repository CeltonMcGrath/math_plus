<?php 
    require("../library/common.php");

	$error = "";
	$terms='';
    if(!empty($_POST)) {
    	if (isset($_POST['terms'])) {
    		header("Location: login.php");
    		die("Registering...");
    	}
    	else {
    		$error = "You must agree to these terms and conditions to
    				register.";
    	}
    }
    else {
    	$terms = file_get_contents("../resources/user_terms_and_conditions.txt");
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
	</head>
	<body>
		<section class="container">
			<div class='login'>
				<h1>Terms and conditions</h1> 
				<div class="scroll">	
					<?php echo $terms ?>			
				</div>
			</div>
			<div class="login-extra">
				<span class='error'><?php echo $error ?></span>
				<form action='registration_terms.php' method='post' >
				<input type="hidden" name='operation' value='register'/>
				<input type='checkbox' name='terms'/>
				I agree to the terms and conditions stated above.
				<input type="submit" value="Register" />
				</form>
			</div>
		</section>
	</body>
</html>
