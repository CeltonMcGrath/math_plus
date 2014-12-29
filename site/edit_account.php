<?php 
    require("../common.php"); 
    include '../template/head.php';
    include '../template/header.php';
    include '../public/user_registration/user_register.php';
    include '../public/user_registration/user_maintenance.php';
    
    $passwordSuccess = $passwordErr = $emailSuccess = $emailErr = "";
    
    // Check if update form has been submitted, and what update form.
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
    			if (updateEmail($_SESSION['user']['user_id'], 
    					$_POST['email'], $db)) {
    						
    				$success = "Email successfully updateded.
    						An activation link has been sent to your email. 
    						Please close your browser and your account will be
    						 inactive until your activate via this link. 
    						(Please check your junk/spam email folders.)";
    				$_SESSION['user'] = null;
    			}
    			else {
    				$emailErr = "Update unsuccessful. Please try again or contact
    						the system administrator.";
    			}
    		}
    	}
    	elseif ($_POST['update']=='password') {
    		/* Update user's password*/
    		// Check for valid current password
    		if (false) {
    			
    		}
    		//  Check for valid format of proposed password
    		if (!validPassword($_POST['newPassword'])) {
    			$passwordError = "Invalid password.";
    		}
    		// Check for matching passwords
    		elseif ($_POST['newPassword'] != $_POST['newPassword2']) {
    			$passwordError = "Passwords do not match.";
    		} 
    		// Attempt password update
    		else {
    			if (updatePassword($_SESSION['user']['user_id'], $_POST['newPassword'], $db)) {
    				$success = "Password successfully updated. 
    						Use your new password for your next login.";
    			}
    			else {
    				$passwordError = "Update unsuccessful. Please try again or contact
    						the system administrator.";
    			}
    		}
    	}
    	else {
    		/* Updates user's listserv settings. */
    		if (updateListserv($_SESSION['user']['user_id'], 
    				isset($_POST['listserv']), $db)) {
    					
    					$success = "Mailing list settings successfuly updated.";
    				}
    	}
    }   
?> 
	<section class="content">
		<h1>Edit Account</h1>
		<span class="success"><?php echo $success?></span>
		<section id="accordion">
			<div class="contact">
				<input class='accordion' type='checkbox' id='email'/>
   				<label for="email">Update email address</label>
   				<article>
					<form action="edit_account.php" method="post"> 
						<input type="hidden" name="update" value="email" />
					    Current email: <?php echo $_SESSION['user']['email']?>
					    <br /> <br />  
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
				</article>
			</div>
		</section>
		<section id="accordion">
			<div class="contact">
				<input class='accordion' type='checkbox' id='password'/>
   				<label for="password">Update password</label>
   				<article>
					<form action="edit_account.php" method="post"> 
						<span class="error"><?php echo $passwordErr?>
							</span>
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
				</article>
			</div>
		</section>
		<section id="accordion">
			<div class="contact">
				<input class='accordion' type='checkbox' id='listserv'/>
   				<label for="listserv">Update mailing list settings</label>
   				<article>
					<form action="edit_account.php" method="post"> 
						<input type="hidden" name="update" value="listserv" />
						Would you like receive email notifications about 
						upcoming programs?
				    	<br />
				    	<input type="checkbox" class="regular" 
				    		name="listserv" checked>Yes
					    <br /><br /> 
					    <input type="submit" 
					    	value="Update mailing list settings" /> 
					</form>
				</article>
			</div>
		</section>
	</section>
    <?php include '../template/footer.php';?>
</html>
