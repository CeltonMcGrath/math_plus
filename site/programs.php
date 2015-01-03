<?php  
    require("../library/common.php");     
    include '../library/Student.php';    
    
    if (empty($_POST)) { 
    	//Redirect to students.php
    	header("Location: students.php");
    	die("Redirecting to students page.");
    	
    }
    
    $student = new Student($_POST['student_id'], $db);
    echo $student->student_id;	
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
    ?>

	<section class='content'>
		<?php $student->student_id; ?>
		<h1>Select programs for 
			<?php $student->printName(); ?>
		</h1>
		<section id='accordion'> 
			<form action='cart.php' method='post'>
				<input type='hidden' name='operation'
					value='update_student' />
				<input type='hidden' name='student_id' 
 					value='<?php $student->printId(); ?>' />
					<?php $student->displayAllPrograms(); ?>
    			<input type='submit' value='Add programs to cart' />
    		</form>
		</section>
	</section>
	<?php include '../library/site_template/footer.php';?>
</html>	


