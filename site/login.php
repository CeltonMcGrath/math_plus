<?php 
    require("../library/common.php");
    include '../library/User.php';
    include '../library/forms/html_Generator.php';
    
    $hg = new html_Generator();
    
    // Redisplay user email if they have a login email.
    $submitted_email = '';
    $error = '';
    $success = ''; 
	
    if(!empty($_POST)) { 
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
    } elseif(isset($_SESSION['registration_data'])) {
        $data = $_SESSION['registration_data'];
        unset($_SESSION['registration_data']);
        if (User::createUser($data['email'], $data['password'], 
        		$data['listserv'], $db)) {
        			$success = "Registration a success.
        			An activation link has been sent to your email.
        			 You must activate your account via this link.
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
		<div class="public-area">
			<div class="public-area-image-sigma">
				<img class='img-responsive' src='../resources/sigma.png'/>
			</div>
			<div class="public-area-image-banner">
				<img class='img-responsive' src='../public_html/math_webheader-1.png'/>
			</div>
			<div class="public-area-content">
				<h2>Sign in to MathPlus</h2>
				<?php 
					echo $hg->errorMessage($error);
					echo $hg->successMessage($success);		
				?>
				<form action="login.php" method="post"><fieldset>
					
					<div class='form-group'> 
						<label for="inputEmail" class="sr-only">Email address</label>
						<input type="email" id="inputEmail" 
							class="form-control input-lg" 
							placeholder="Email address" name='email'
							value="<?php echo $submitted_email ?>" 
							required autofocus>
						<label for="inputPassword" class="sr-only">Password</label>
						<input type="password" id="inputPassword" 
							class="form-control input-lg" 
							name="password" placeholder="Password" required>
					</div>
					<!-- Button -->
					<div class='form-group'>
						<button type='submit' class="btn btn-lg btn-primary btn-block">
							Login
						</button>
					</div>
				</fieldset></form>
				<a href="register.php">Create an account</a>
				<br />
				<a href="forgot_password.php">Forgot your password?</a>
			</div>	 
    	</div>
	</body>
</html>
