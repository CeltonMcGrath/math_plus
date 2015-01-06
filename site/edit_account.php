<?php 
    require("../library/common.php"); 
    include '../library/user_registration/user_register.php';
    include '../library/user_registration/user_maintenance.php';
    include '../library/Form_Validator.php';
    include '../library/forms/Form_Generator.php';
    include '../library/forms/html_Generator.php';
    
    $fg = new Form_Generator();
    $hg = new html_Generator();
    
    $error = '';
    $success = '';
    
    // Check if form has been submitted
    if(!empty($_POST)) {
    	$form_validator = new Form_Validator();
    	$result = $form_validator->validateRegistrationPost($_POST);
    	if ($result != -1) {
    		$error = $result;
    	}
    	else {
    		$data = $form_validator->sanitizeAccountUpdatePost($_POST);
    		// Check for type of updates
    		if ($data['update']=='email') {
    			//Check if user exists
    			if (userExists($data['email'], $db)) {
    				$error = "This email is already in use.";
    			}
    			else {
    				// Update email
    				updateEmail($_SESSION['user']['user_id'], $data['email'], 
    					$db);
    				$success = "Email successfully updated.
    						An activation link has been sent to your email.
    						Please close your browser and your account will be
    						 inactive until your activate via this link.
    						(Please check your junk/spam email folders.)";
    			}
    		}
    		// Password update
    		elseif ($data['update']=='password') {
    			//Check old password
    			if (!correctPassword($data['oldPassword'])) {
    				$error = "Incorrect credentials."
    			}
    			else {
    				//Update passworld
    				updatePassword($_SESSION['user']['user_id'],
    				$data['newPassword'], $db);
    				$success = "Password successfully updated.
    						Use your new password for your next login.";
    			}    			
    		}
    		// Listserv setting update
    		else {
    			updateListserv($_SESSION['user']['user_id'], $data['listserv'], 
    				$db);
    			$success = "Mailing list settings successfuly updated.";
    		}
    	}
    }
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?> 
	<section class="content">
		<h1>Edit Account</h1>
		<span class="error"><?php echo $error?></span>
		<span class="success"><?php echo $success?></span>		
		<section id="accordion">
			<?php
			//Link to guardians.php
			echo $hg->accordionBox('guardians', 
				'<a href="guardians.php">Manage parent/guardian contacts</a>', 
				'');
			//Link to students.php
			echo $hg->accordionBox('students', 
				'<a href="students.php">Manage student and programs</a>', '');
			//Email updater
			echo $hg->accordionBox('email', 'Update email address', 
				fg->emailUpdateForm());
			//Password updater
			echo $hg->accordionBox('password', 'Update password', 
				$fg->passwordUpdateForm());
			//Listserv updater
			echo $hg->accordionBox('listserv', 'Update mailing list settings', 
				$fg->listservUpdateForm($_SESSION['user']['email']));
			?>
		</section>
	</section>
    <?php include '../library/site_template/footer.php';?>
</html>
