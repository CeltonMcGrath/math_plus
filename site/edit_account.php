<?php 
    require("../library/common.php"); 
    include '../library/User.php';
    include '../library/Form_Validator.php';
    include '../library/forms/Form_Generator.php';
    include '../library/forms/html_Generator.php';
    
    $fg = new Form_Generator();
    $hg = new html_Generator();
    $user = new User($_SESSION['user']['user_id'], $db);
    
    $error = '';
    $success = '';
    // Check if form has been submitted
    if(!empty($_POST)) {
    	$form_validator = new Form_Validator();
    	$result = $form_validator->validateAccountUpdatePost($_POST);
    	if ($result != -1) {
    		$error = $result;
    	}
    	else {
    		$data = $form_validator->sanitizeAccountUpdatePost($_POST);
    		// Check for type of updates
    		if ($data['update']=='email') {
    			//Check if user exists
    			if (User::userExists($data['email'], $db)) {
    				$error = "This email is already in use.";
    			}
    			else {
    				// Update email
				$user->updateEmail($data['email']);
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
    			if (!$user->correctPassword($data['oldPassword'])) {
    				$error = "Incorrect credentials.";
    			}
    			else {
    				//Update passworld
    				$user->updatePassword($data['newPassword']);
    				$success = "Password successfully updated.
    						Use your new password for your next login.";
    			}    			
    		}
    		// Listserv setting update
    		else {
    			$user->updateListserv($data['listserv']); 
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
				$fg->emailUpdateForm($_SESSION['user']['email']));
			//Password updater
			echo $hg->accordionBox('password', 'Update password', 
				$fg->passwordUpdateForm());
			//Listserv updater
			echo $hg->accordionBox('listserv', 'Update mailing list settings', 
				$fg->listservUpdateForm($user->getListserv()));
			?>
		</section>
	</section>
    <?php include '../library/site_template/footer.php';?>
</html>
