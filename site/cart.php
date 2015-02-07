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
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
    echo "<section class='content'>";
	if(!$cart->isEmpty()) {
		echo "
		<h1>Program shopping cart:</h1>
		<span class='success'>".$success."</span>
		<span class='error'>".$error."</span>	
    	<form method='post' action='cart.php'>
        	<ul>";
        $total = $cart->displayCart();
		echo "</ul>
        	<input type='submit' name='delete' 
        		value='Delete selected programs'/>
        	<br />
        	Enter bursary code:<input type='text' name='bursary_id'/>
			<input type='submit' name='bursary'
				value='Apply bursary to selected program'/>
			<article> Total: ".number_format($total, 2)."</article>
       	</form>
		<br />";
		if ($total > 0) {
			echo "<FORM METHOD='POST' 
					ACTION='https://esqa.moneris.com/HPPDP/index.php'> 
				<INPUT TYPE='HIDDEN' NAME='ps_store_id' VALUE='XU4D4tore1'> 
				<INPUT TYPE='HIDDEN' NAME='hpp_key' VALUE='hpHQNQ99HJ28'>
				<INPUT TYPE='HIDDEN' NAME='charge_total' VALUE=".$total.">
				<!--MORE OPTIONAL VARIABLES CAN BE DEFINED HERE -->
				<INPUT TYPE='SUBMIT' NAME='SUBMIT' 
					VALUE='Click to proceed to Secure Page'>
			</FORM>";
		}
		else {
			// Go to confirm
		}					
    }  
    else {
		echo '<h1>Your cart is empty. 
			Add programs under student management panel.</h1>';
    }
	echo "</section>";
	include '../library/site_template/footer.php';
	?>	
</html>
