<?php 
    require("../library/common.php"); 
    include '../library/Cart.php';
    include '../library/forms/html_Generator.php';
    
    $hg = new html_Generator();
 
    /*Load previous cart contents*/
    $cart = new Cart($_SESSION['user']['user_id'], $db);
    
    $success = "";
    $error = "";

    if (!empty($_POST)) {
    	// Delete cart items
    	if (isset($_POST['delete'])) {
    		$cart->deletePrograms($_POST['selected_programs']);	
    	}
    	// Apply bursary code
    	elseif (isset($_POST['bursary'])) {
    		if (!isset($_POST['selected_programs'])) {
				$error = 'Please select a program.';
			}
			elseif (count($_POST['selected_programs'])>1) {
    			$error = 'You may only apply code to one program.';
    		}
    		elseif (!$cart->validBursary($_POST['bursary_id'], 
    				$_POST['selected_programs'][0])) {
    			$error = 'Code is incorrect.';
    		}
    		else {
    			$cart->applyBursary($_POST['bursary_id'], 
    					$_POST['selected_programs']);
    			$success = "Bursary code applied.";
    		}
    	}
    	/* User was redirected from program selection for
    	 * some student. Add programs to cart. */
    	else {
    		$cart->addPrograms($_POST['student_id'], $_POST['program_group']);
    	}  	
    }    
?>

<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
	<div class="container">		
		<?php 
		if(!$cart->isEmpty()) {
			echo $hg->errorMessage($error).$hg->successMessage($success)."	
			<form class='form-horizontal' method='post' action='cart.php'>
    		<fieldset>
    		<ul class='list-group'>
    		<h3 class='list-group-item'>
  					Shopping cart
			</h3>";
        	$total = $cart->displayCart();
			echo "    		
    		<li class='list-group-item'>
    			<div class='form-group'>
					<label class='col-md-4 control-label' for='bursary_code'>Enter bursary code</label>
					<div class='col-md-4'>
						<input id='bursary_code' name='bursary_id' 
    					type='text' class='form-control input-md' />		
					</div>
    				<div class='col-md-4'>
    					<button type='submit' id='bursary' 
    						name='bursary' 
    						class='btn btn-md btn-primary btn-block'
    						value='0'>
    							Apply bursary to selected program
    					</button>	
					</div>
				</div>
    		</li>
    		<li class='list-group-item'>
    			<div class='form-group'>
    				<div class='col-md-4'>
    					<button type='submit' id='delete' 
    						name='delete' 
    						class='btn btn-md btn-primary btn-block'
    						value='0'>
    							Delete selected programs
    					</button>	
					</div>
				</div>
    		</li>
    		<li class='list-group-item'>
				<article> Total: ".number_format($total, 2)."</article>
    		</li>
			</fieldset>
    		</form>";
			if ($total > 0) {
				echo "
    			<li class='list-group-item'>
    				<FORM METHOD='POST' 
						ACTION='https://esqa.moneris.com/HPPDP/index.php'> 
					<INPUT TYPE='HIDDEN' NAME='ps_store_id' VALUE='XU4D4tore1'> 
					<INPUT TYPE='HIDDEN' NAME='hpp_key' VALUE='hpHQNQ99HJ28'>
					<INPUT TYPE='HIDDEN' NAME='charge_total' VALUE=".$total.">
					<!--MORE OPTIONAL VARIABLES CAN BE DEFINED HERE -->
					<INPUT TYPE='SUBMIT' NAME='SUBMIT' 
						VALUE='Click to checkout'>
				</FORM>
    			</li></ul>";
			}
			else {
				echo "<li class='list-group-item'>
    				<FORM METHOD='POST' 
						ACTION='confirm.php'> 
					<INPUT TYPE='SUBMIT' NAME='SUBMIT' 
						VALUE='Click to checkout'>
				</FORM>
    			</li></ul>";
			}					
    	}  
    	else {
			echo '<h3>Your cart is empty. Add programs under student 
    				management panel.</h3>';
    	} 
    	?>	
	</div>
  </body>
</html>



