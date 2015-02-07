<?php 
    require("../library/common.php");
    include '../library/User.php'; 
    
    // Redisplay user email if they have a login email.
    $submitted_email = '';
    $error = '';
    $success = ''; 
	
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
   
?> 

<html>
	<?php include '../library/site_template/head_public_area.php' ?>
	<body>
		<div class="container">
		      <form class="form-signin" action="login.php" method="post" >
		        <h2 class="form-signin-heading">Please sign in</h2>
		        <input type=hidden name='operation' value='login' />
		        <h5 class="form-signin-heading">
		        	<?php echo $error; echo $success ?>
		        </h5>
		        <label for="inputEmail" class="sr-only">Email address</label>
		        <input type="email" id="inputEmail" class="form-control" 
		        	placeholder="Email address" name='email'
		        	value="<?php echo $submitted_email ?>" 
		        	required autofocus>
		        <label for="inputPassword" class="sr-only">Password</label>
		        <input type="password" id="inputPassword" class="form-control" 
		        	name="password" placeholder="Password" required>
		        <button class="btn btn-lg btn-primary btn-block" 
		        	type="submit">Sign in</button>
			 </form>
		     <form class="form-signin" action="register.php">
		     	<button class="btn btn-lg btn-primary btn-block" 
		        	type="submit">Register</button>
		        	<br />
		        	<a href="forgot_password.php">Forgot your password?</a>
			 </form>	 
    	</div>
	</body>
</html>
