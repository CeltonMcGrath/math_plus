<?php 
    require("../library/common.php");
    include '../library/forms/html_Generator.php';

    $hg = new html_Generator();
    
	$error = "";
	$terms='';
    if(!empty($_POST)) {
    	if (isset($_POST['terms'])) {
    		header("Location: login.php");
    		die("Registering...");
    	}
    	else {
    		$error = "You must agree to these terms and conditions to
    				register.";
    	}
    }
    
    $terms = file_get_contents("../resources/user_terms_and_conditions.html"); 
?> 

<!DOCTYPE html>
<html lang="en">
	<?php include '../library/site_template/head_public_area.php' ?>
	<body>
		<div class="public-area">
			<div class="public-area-image-banner">
				<img class='img-responsive' src='../public_html/math_webheader-1.png'/>
			</div>
			<h2>Terms & Conditions</h2>
			<div class="well terms-scroll">
				<?php include '../resources/user_terms_and_conditions.html' ?>
			</div>
			<?php echo $hg->errorMessage($error); ?>
			<form action="registration_terms.php" method="post"><fieldset>
				<div class='form-group'> 
					<label for="checkbox-terms">
						<input type="checkbox" id="checkbox-terms" name="terms" />
						I agree to the terms and conditions stated above.
					</label>
				</div>
				<div class='form-group'>
					<button type='submit' class="btn btn-lg btn-primary btn-block">
						Complete registration
					</button>
				</div>
			</fieldset></form>
    	</div>
	</body>
</html>
