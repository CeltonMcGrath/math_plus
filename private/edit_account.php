<?php 
    require("../common.php"); 
    include '../template/head.php';
    include '../template/header.php';
    include '../template/footer.php';
    include '../public/user_registration/user_register.php';
    include '../public/user_registration/user_maintenance.php';
    
    $passwordSuccess = $passwordErr = $emailSuccess = $emailErr = "";
    
    // This if statement checks to determine whether the edit form has been submitted 
    if(!empty($_POST)) { 
    	if ($_POST['update']=='email') {
    		/* Update user's email */
    		// Check for valid email
    		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    			$emailErr = "Update unsuccessful: please enter valid email.";
    		}  
    		// Check for matching emails
    		elseif ($_POST['email']!=$_POST['email2']) {
    			$emailErr = "Update unsuccessful: emails do not match.";
    		} 
    		// Check the email does not already exist.
    		elseif (userexists($_POST['email'], $db)) {
    			$emailErr = "Update unsuccessful: user already exists in system.";
    		} 
    		else {
    			if (updateEmail($_SESSION['user']['user_id'], $_POST['email'], $db)) {
    				$emailSuccess = "Update successful. An activation link has been 
    						sent to your email. Please close your browser and 
    						your account will be inactive until your activate 
    						via this link. 
    						(Please check your junk/spam email folders.)";
    				
    				$_SESSION['user'] = null;
    			}
    			else {
    				$emailErr = "Update unsuccessful. Please try again or contact
    						the system administrator.";
    			}
    		}
    	}
    	else {
    		/* Update user's password*/
    		// Check for valid current password
    		if (false) {
    			
    		}
    		//  Check for valid format of proposed password
    		if (false) {
    			$passwordError = "Update unsuccessful: invalid password.";
    		}
    		// Check for matching passwords
    		elseif ($_POST['newPassword'] != $_POST['newPassword2']) {
    			$passwordError = "Update unsuccessful: passwords do not match.";
    		} 
    		// Attempt password update
    		else {
    			if (updatePassword($_SESSION['user']['user_id'], $_POST['newPassword'], $db)) {
    				$passwordSuccess = "Update successful. Use your new password for your 
    					next login.";
    			}
    			else {
    				$passwordError = "Update unsuccessful. Please try again or contact
    						the system administrator.";
    			}
    		}
    	}
    }   
?> 
	<body>
		<h1>Edit Account</h1> 
		
		<h2>Update email address</h2> 
		<form action="edit_account.php" method="post"> 
			<input type="hidden" name="update" value="email" />
		    Current email: <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>
		    <br /> <br />  
		    <span class="success"><?php echo $emailSuccess?></span>
			<span class="error"><?php echo $emailErr?></span> 
			<br /> <br /> 
		    New email:<br /> 
		    <input type="email" name="email" value="" />
		    <br /><br /> 
		    Re-enter new email:<br /> 
		    <input type="email" name="email2" value="" />
		    <br /><br /> 
		    <input type="submit" value="Update email" /> 
		</form>
		
		<h2>Update password</h2>
		<span class="success"><?php echo $passwordSuccess?></span>
		<span class="error"><?php echo $passwordErr?></span>
		<br /><br />
		<form action="edit_account.php" method="post"> 
			<input type="hidden" name="update" value="password" />
		    Current password:<br /> 
		    <input type="password" name="oldPassword" value="" />
		    <br /><br /> 
		    New Password:<br /> 
		    <input type="password" name="newPassword" value="" /> 
			<br /><br /> 
		    Re-enter new Password:<br /> 
		    <input type="password" name="newPassword2" value="" />
		    <br /><br /> 
		    <input type="submit" value="Update password" /> 
		</form>
	</body>
</html>