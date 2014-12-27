<?php 
    require("../common.php"); 
    include 'Student.php';

    /* If form has been submitted, then user has been referred to
     * the shopping cart by adding programs for a specific student.
     * Add these programs to the session shopping cart.
     */
    if (!empty($_POST)) {
    	$student_id = $_POST['student_id'];
    	// Create session shopping cart array if not already created
    	if (!$_SESSION['cart_programs']) {
    		$_SESSION['cart_programs'] = [];
    	}
   		
    	$selectedPrograms = $_POST['program_group'];
    	for ($i=0; $i < count($selectedPrograms); $i++) {
    		//Get program information: name, cost, 
    			
    		//Create student-program array
    		$new_program[] =
    			array('student_id' => $student_id, 
			'program_id' => $selectedPrograms[i]);
    		
    		//Add student-program array to session array
    		$_SESSION["cart_programs"] = 
    			array_push($_SESSION["cart_programs"], $new_program);
    	}
    }
    
    include '../template/head.php';
    include '../template/header.php';
    echo "<section class='content'>";

	if(isset($_SESSION["products"])) {
		
        $total = 0;
        echo '<form method="post" action="cart.php">';
        echo '<ul>';
        $cart_items = 0;
        foreach ($_SESSION["cart_programs"] as $cart_itm) {
           $student = new Student($cart_itm['student_id'], $db);
           $total += $student->programCartDisplay($cart_itm['program_id']);
        }
        echo '</ul>';
        echo '<span class="check-out-txt">';
        echo '<strong>Total : $".$total."</strong>';
        echo '</span>';
        echo '</form>';
        
        echo "<form method='post' 
        		action='https://www.paypal.com/cgi-bin/webscr' >
	        <input type='hidden' name='cmd' value='_xclick'>
	        <input type='hidden' name='business' value='info@freshcoffeenetwork.com'>
	        <input type='hidden' name='item_name' value='1 coffee bean'>
	        <input type='hidden' name='currency_code' value='CAD'>
	        <input type='hidden' name='amount' value='12.00'>
	        <input type='image' 
	        		src='http://www.paypal.com/en_US/i/btn/x-click-but01.gif' 
	        		name='submit' 
	        		alt='Pay with Paypal.'>
        </form>";
    } 
    else {
		echo '<h1>Your cart is empty.</h1>';
    }
 
	echo "</section>";
	include '../template/footer.php';
	?>
</html>
