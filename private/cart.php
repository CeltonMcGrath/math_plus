<?php 
    require("../common.php"); 
    include 'student_registration/Student.php';

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
    	foreach ($selectedPrograms as $program=>$program_id) {
    		//Get program information: name, cost, 
    			
    		//Create student-program array
    		$new_program[] =
    			array('student_id' => $student_id, 
			'program_id' => $program_id);
    		
    		//Add student-program array to session array
    		array_push($_SESSION["cart_programs"], $new_program);
    	}
    }
    
    include '../template/head.php';
    include '../template/header.php';
    echo "<section class='content'>";

	if(isset($_SESSION["cart_programs"])) {
		
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
	        <input type='hidden' name='cmd' value='_cart'>
	        <input type='hidden' name='business' value='info@freshcoffeenetwork.com'>
	        <input type='hidden' name='item_name_1' value='coffee bean'>
	        <input type='hidden' name='amount_1' value='12.00'>
	        <input type='hidden' name='item_name_2' value='coffee filter'>
	        <input type='hidden' name='amount_2' value='13.00'>
	        <input type='hidden' name='currency_code' value='CAD'>
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
