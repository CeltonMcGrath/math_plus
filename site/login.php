<?php 
    require("../library/common.php");
    include '../library/User.php'; 
    
    // Redisplay user email if they have a login email.
    $submitted_email = '';
    $error = '';
    
	
    if(!empty($_POST)) { 
    	if ($_POST['operation']=='login') {
    		if (User::userExists($_POST['email'], $db)) {
    			$user = new User($_POST['email'], $db);
    			if ($user->correctPassword($_POST['password']) && $user->getStatus() == 1) {
    				// Login successful.
    				// Only store user id and email in session variable.
    				$_SESSION['user'] = array('user_id'=>$user->getId(), 'email'=>$_POST['email']);
    		
    				// Redirect the user to the private members-only page.
    				header("Location: splash.php");
    				die("Logging in...");
    			}
    			elseif ($user->correctPassword($_POST['password']) && $user->getStatus() == 0) {
    				$error = "Account not activated.";
    			}
    			else {
    				$error = "Incorrect username and/or password.";
    			}
    		}
    		else {
    			$error = "Incorrect username and/or password.";
    		}
    		$submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
    	}
    } elseif(isset($_SESSION['registration_data'])) {
        $data = $_SESSION['registration_data'];
        unset($_SESSION['registration_data']);
        if (User::createUser($data['email'], $data['password'], 
        		$data['listserv'], $db)) {
        			$error = "Registration a success.
        			An activation link has been sent to your email.
        			 You must activitate your account via this link.
        			Please check your spam/junk folders.";
        }
        else {
        	$error = "Registration failed.
        				Please try again or contact administrator.";
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
	</head>
	<body>
		<section class="container">
			<div class="login">
				<h1>Login</h1> 
				<span class="error"><?php echo $error ?></span>
				<br /><br />
				<form action="login.php" method="post" ">
				    <input type="hidden" name="operation" value="login" />
				    Email:<br /> 
				    <input type="email" name="email" 
				    	value="<?php echo $submitted_email; ?>" />
				    <br /><br /> 
				    Password:<br /> 
				    <input type="password" name="password" value="" /> 
				    <br /><br /> 
				    <input type="submit" value="Login" />
				</form> 				
			</div>
			<div class="login-extra">
				<a href="register.php">Register</a>
				<a href="forgot_password.php">Forgot password</a>
			</div>
		</section>
	</body>
</html>
