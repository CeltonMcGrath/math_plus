<?php 
    require("../common.php"); 
    include '../template/head.php';
    include '../template/header.php';  

    // Update cart coming from student program selection page
    if (!empty($_POST)) {
    	//First item in POST array should be student ID
    	//Later items are program id's
    	$student_id = $_POST['student_id'];
    	if (!$_SESSION['cart_programs']) {
    		$_SESSION['cart_programs'] = [];
    	}
    	
    	$selectedPrograms = $_POST['program_group'];
    	for ($i=0; $i < count($selectedPrograms); $i++) {
    		//Get program information: name, cost, 
    		
    		//Create student-program array
    		$new_program[] =
    			array('student_id' => $student_id, 
			'program_id' => $selectedPrograms['program_id']);
    		
    		//Add student-program array to session array
    		$_SESSION["cart_programs"] = 
    			array_push($_SESSION["cart_programs"], $new_program);
    	}
    }
    
    echo "<section class='content'>";

	if(isset($_SESSION["products"])) {
        /*$total = 0;
        echo '<form method="post" action="PAYMENT-GATEWAY">';
        echo '<ul>';
        $cart_items = 0;
        foreach ($_SESSION["products"] as $cart_itm) {
           $student = new Student($cart_itm['student_id'], $db;
           $cost = student->programCartDisplay($cart_itm['program_id']);
           
        }
        echo '</ul>';
        echo '<span class="check-out-txt">';
        echo '<strong>Total : '.$currency.$total.'</strong>  ';
        echo '</span>';
        echo '</form>';*/
        
    } 
    else {
		echo '<h1>Your cart is empty.</h1>';
    }
 
	echo "</section>";
	include '../template/footer.php';
	?>
</html>
