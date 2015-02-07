<?php
    // First we execute our common code to connection to the database and start the session 
    require('../library/common.php');   
    include '../library/User.php';
    include '../library/Form_Validator.php';
    include '../library/forms/Form_Generator.php';
	
    $fg = new Form_Generator();
	
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
			$data = $form_validator->sanitizeRegistrationPost($_POST);
			// Check for uniqueness of email
			if (User::userExists($data['email'], $db)) {
				$error = "This email is already in use.";
			}
        	else {
        		$_SESSION['registration_data'] = $data;
        		header("Location: registration_terms.php");
        		die("Read the user terms and conditions.");
        	}
	    }
    }
?> 

<html>
	<?php include '../library/site_template/head_public_area.php'; ?>
	<div class='container'>
		<?php echo $fg->registrationForm($error, $success) ?>		
	</div>	
</html>

