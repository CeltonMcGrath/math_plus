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
    				$error = "Incorrect password.";
    			}
    			else {
    				//Update password
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
    
?>


<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
	<div class='container'>
		<h1>Edit Account</h1>
		<?php 
			echo $hg->errorMessage($error);
			echo $hg->successMessage($success);		
		?>
		<div class="accordion" id="accordion">
			<?php
			//Email updater
			echo $hg->bootstrapAccordion('email', 'Update email address', 
				$fg->emailUpdateForm($_SESSION['user']['email']));
			//Password updater
			echo $hg->bootstrapAccordion('password', 'Update password', 
				$fg->passwordUpdateForm());
			//Listserv updater
			echo $hg->bootstrapAccordion('listserv', 'Update mailing list settings', 
				$fg->listservUpdateForm($user->getListserv()));
			?>
		</div>
	</div>
  </body>
</html>


