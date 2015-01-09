<?php 
    require("../library/common.php");
	  
    if(!empty($_POST)) {
    	$data = $_SESSION['registration_data'];
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
			<h1>Terms and conditions</h1> 
			<div class="scroll">	
				<?php echo $terms?><			
			</div>
			<div class="login-extra">
				<form action='register.php' method='post' >
				<input type='checkbox' class='regular' name='terms' >
				</form>
			</div>
		</section>
	</body>
</html>
