<?php 
    require("../library/common.php"); 
    include '../library/Cart.php';
 
    /*Load previous cart contents*/
    $cart = new Cart($_SESSION['user']['user_id'], $db);

    $success = "";
    $error = "";

    if (!empty($_POST)) {
    	// Delete cart items
    	if (isset($_POST['delete'])) {
    		$cart->deletePrograms($_POST['selected_programs']);	
    	}
    	// Apply bursary codes
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
		<h1>Shopping cart</h1>
		<?php 
		if(!$cart->isEmpty()) {
			echo "
			<h3>".$success.$error."</h3>
    		<form method='post' action='cart.php'>
    		<div class='panel panel-default'>
			  <div class='panel-heading'>
			    <h3 class='panel-title'>Panel title</h3>
			  </div>";
        	$total = $cart->displayCart();
			echo "
    		<div class='panel-body'>
    			<input type='submit' name='delete' 
        		value='Delete selected programs'/>
 			</div>
    		<div class='panel-body'>
	        	Enter bursary code:<input type='text' name='bursary_id'/>
				<input type='submit' name='bursary'
					value='Apply bursary to selected program'/>
				<article> Total: ".number_format($total, 2)."</article>
	       		</form>
    		</div>";
			if ($total > 0) {
				echo "
    			<div class='panel-body'>
    				<FORM METHOD='POST' 
						ACTION='https://esqa.moneris.com/HPPDP/index.php'> 
					<INPUT TYPE='HIDDEN' NAME='ps_store_id' VALUE='XU4D4tore1'> 
					<INPUT TYPE='HIDDEN' NAME='hpp_key' VALUE='hpHQNQ99HJ28'>
					<INPUT TYPE='HIDDEN' NAME='charge_total' VALUE=".$total.">
					<!--MORE OPTIONAL VARIABLES CAN BE DEFINED HERE -->
					<INPUT TYPE='SUBMIT' NAME='SUBMIT' 
						VALUE='Click to proceed to Secure Page'>
				</FORM>
    			</div>";
			}
			else {
				// Go to confirm
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



