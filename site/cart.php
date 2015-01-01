<?php 
    require("../library/common.php"); 
    include '../library/Cart.php';
 
    /*Load previous cart contents*/
    $cart = new Cart(_SESSION['user_id']);
    
    if (!empty($_POST)) {
    	// Delete cart items
    	if ($_POST['operation']=='update_cart') {
    		$cart->deletePrograms($_POST['delete_group']);	
    	}
    	// Apply bursary codes
    	elseif ($_POST['operation']=='apply_bursary') {
    		$cart->applyBursary($_POST['bursary_id']);
    	}
    	/* User was redirected from program selection for
    	 * some student. Add programs to cart. */
    	else {
    		$student_id = $_POST['student_id'];
    		$selectedPrograms = $_POST['program_group'];
    		foreach ($selectedPrograms as $program_id) {
    			$cart->addProgram($student_id, $program_id);
    		}
    	}  	
    }    
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
    echo "<section class='content'>";
	if(!$cart->isEmpty()) { ?>
		<span class='success'>$success</span>;
		<span class='error'>$error</span>;		
    	echo "<form method='post' action='cart.php'>
        		<input type='hidden' name='operation' value='update_cart'/>
        		<ul>";
        $total = cart->displayCart();
		echo "</ul>
				<article>Total: ".$total."</article>
        		<input type='submit' value='Delete selected programs'/>
       		</form>";
		echo "<br />";
		echo "<form method='post' action='cart.php'>
        		<input type='hidden' name='operation' value='apply_bursary'/>
				Enter bursary code:<input type='text' name='bursary_id'/>
				<input type='submit' value='Apply bursary to selected program'/>
       		</form>";
		echo "<br />";
		echo "<form method='post' action='confirm.php'>
			<input type='hidden' name='cart_total' value='".$total."'/>
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
