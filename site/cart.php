<?php 
    require("../library/common.php"); 
    include '../library/Cart.php';
 
    /*Load previous cart contents*/
    $cart = new Cart(_SESSION['user_id']);

    $success = "";
    $error = "";

    if (!empty($_POST)) {
    	// Delete cart items
    	if (isset($_POST['delete'])) {
    		$cart->deletePrograms($_POST['selected_programs']);	
    	}
    	// Apply bursary codes
    	elseif (isset($_POST['bursary'])) {
    		if (count($_POST['selected_programs'])>1) {
    			$error = 'You may only apply code to one program.';
    		}
    		elseif (!cart->validBursary($_POST['bursary_id'], 
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
	if(!$cart->isEmpty()) { ?>
		<span class='success'>$success</span>
		<span class='error'>$error</span>	
    	<form method='post' action='cart.php'>
        	<ul>
        	<?php $total = $cart->displayCart(); ?>
			</ul>
			<article> Total: <?php echo $total ?></article>
        	<input type='submit' name='delete' 
        		value='Delete selected programs'/>
        	<br />
        	Enter bursary code:<input type='text' name='bursary_id'/>
			<input type='submit' name='bursary'
				value='Apply bursary to selected program'/>
       	</form>
		<br />
		<form method='post' action='confirm.php'>
			<input type='hidden' name='cart_total' 
				value='<?php echo $total ?>'/>
        	<input type='submit' value='Proceed to payment'/>
       	</form>";			
    <?php }  
    else {
		echo '<h1>Your cart is empty.</h1>';
    }
	echo "</section>";
	include '../library/site_template/footer.php';
	?>	
</html>
