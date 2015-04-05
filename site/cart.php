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

    $contents = $cart->getFormattedContents();
    $total = number_format(Cart::getTotal($contents), 2);
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
				</h3>
			<li class='list-group-item'>
			<div class='form-group'>
    		<div class='col-md-4'>";
			foreach ($contents as $index=>$cart_item) {
				$student_name = $cart_item['student_name'];
				$program_name = $cart_item['program_name'];
				$cost = $cart_item['cost'];
				echo "
					<div class='checkbox'>
						<label for='$index'>
							<input 
								id='$index' name='selected_programs[]'
								value='".$index."' type='checkbox'
								 />
							".$student_name." - ".$program_name." - ".$cost."
						</label>
					</div>";
			}
			echo "</div></li>  		
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
	    				</div>
	    				<div class='col-md-4'>
	    				</div>
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
	    		<li class='list-group-item pull-right'>
					Total: $total CAD
	    		</li>
			</fieldset>
    		</form>
			</ul>";
			if ($total > 0) {
				echo "
    			<div class='pull-right'>
    				<form method='post' 
						action='https://esqa.moneris.com/HPPDP/index.php'>
					<input TYPE='HIDDEN' NAME='ps_store_id' VALUE='XU4D4tore1'> 
					<input TYPE='HIDDEN' NAME='hpp_key' VALUE='hpHQNQ99HJ28'>
					<input TYPE='HIDDEN' NAME='charge_total' VALUE='$total'>";
					$n = 1;
					foreach ($contents as $index=>$item) {
						echo "
						<input type='hidden' name='id$n'
							value='".$item['student_id']." - ".$item['program_id']."'>
						<input type='hidden' name='description$n' 
							value='".$item['program_name']." - ".$item['student_name']."'> 
						<input type='hidden' name='quantity$n' 
							value='1'>
						<input type='hidden' name='price$n' 
	    					value='".$item['cost']."'>
						<input type='hidden' name='subtotal$n' 
	    					value='".$item['cost']."'>";
	    				$n++;
					}
					echo "<button type='submit' class='btn btn-primary'>
  						Proceed to payment
  					</button>
					</FORM>
				</div>";
			}
			else {
				echo "
    			<div class='pull-right'>
    				<FORM METHOD='POST' 
						ACTION='confirm.php'> 
					<INPUT TYPE='HIDDEN' NAME='no_cost' VALUE='true'> 
					<button type='submit' class='btn btn-primary'>
  						Complete registration
  					</button>
					</FORM>
				</div>";
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



