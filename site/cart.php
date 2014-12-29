<?php 
    require("../library/common.php"); 
    include '../library/Student.php';

    /* If form has been submitted, then user has been referred to
     * the shopping cart by adding programs for a specific student.
     * Add these programs to the session shopping cart.
     */
    if (!empty($_POST)) {
    	if ($_POST['operation']=='update_cart') {
    		foreach ($_POST['delete_group'] as $index) {
				unset($_SESSION['cart_programs'][$index]);
			}	
    	}
    	else {
    		$student_id = $_POST['student_id'];
    		// Create session shopping cart array if not already created
    		if (!isset($_SESSION['cart_programs'])) {
    			$_SESSION['cart_programs'] = [];
    		}
    		 
    		$selectedPrograms = $_POST['program_group'];
    		foreach ($selectedPrograms as $program_id) {
    			//Create student-program array
    			$new_program = array($student_id, $program_id);
    		
    			//Add student-program array to session array
    			array_push($_SESSION["cart_programs"], $new_program);
    		}
    	}
    }
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
    echo "<section class='content'>";
	if(isset($_SESSION["cart_programs"])) {		
        $total = 0;
	$counter = 0;
        echo "<form method='post' action='cart.php'>
        		<input type='hidden' name='operation' value='update_cart' />
        		<ul>";
        foreach ($_SESSION["cart_programs"] as $cart_itm) {
           $student = new Student($cart_itm[0], $db);
           $total += $student->programCartDisplay($cart_itm[1], $counter);
	   	   $counter++;
        }
		echo "</ul>
				<article>Total: ".$total."</article>
        		<input type='submit' value='Update cart' />
       		</form>";
        
        echo "<form action='checkout.php' METHOD='POST'>
		<input type='hidden' name='cart_total' value=".$total." />	
		<input type='image' name='paypal_submit' id='paypal_submit'  
        	src='https://www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif' 
        	border='0' align='top' alt='Pay with PayPal'/>
			</form>";
    } 
    
    else {
		echo '<h1>Your cart is empty.</h1>';
    }
 
	echo "</section>";
	include '../library/site_template/footer.php';
	?>
	
	<!-- Add Digital goods in-context experience.  -->
	<script 
		src='https://www.paypalobjects.com/js/external/dg.js' 
		type='text/javascript'>
	</script>
	<script>
		var dg = new PAYPAL.apps.DGFlow(
		{
			trigger: 'paypal_submit',
			expType: 'instant'
			 /* PayPal will decide the experience type for the buyer based on 
			  * his/her 'Remember me on your computer' option.
			  */
		});
	</script>

</html>
