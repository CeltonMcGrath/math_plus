<?php
    require('../library/common.php');   
    include '../library/User.php';
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



<!DOCTYPE html>
<html>
    <?php include '../library/site_template/head_public_area.php'; ?>
    <body>
		<div class="public-area">
			<div class="public-area-image-sigma">
				<img class='img-responsive' src='../resources/sigma.png'/>
			</div>
			<div class="public-area-image-banner">
				<img class='img-responsive' src='../public_html/math_webheader-1.png'/>
			</div>
			<div class="public-area-content">
				<h2>Create an account</h2>
				<?php 
					echo $hg->errorMessage($error);
					echo $fg->registrationForm($error, $success); 
				?>
			</div>	 
    	</div>
	</body>
</html>