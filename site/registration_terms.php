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
		<div class="container" >
		      <form class="form-signin" action='registration_terms.php' 
		      		method='post' >
		        <h2 class="form-signin-heading">Terms & Conditions</h2>
		        <div class="scroll" style="background: white; max-height: 70%; overflow: scroll;">	
					<?php include '../resources/user_terms_and_conditions.html' ?>			
				</div>
		        <input type="hidden" name='operation' value='register'/>
		        <?php 
					echo $hg->errorMessage($error);		
				?>
		        <label for="inputEmail" class="sr-only">Email address</label>
		        <input type='checkbox' name='terms'/>
				I agree to the terms and conditions stated above.
		        <button class="btn btn-lg btn-primary btn-block" 
		        	type="submit">Complete registration</button>
			 </form>	 
    	</div>
	</body>
</html>
