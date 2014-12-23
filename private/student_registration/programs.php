<?php  
    require("../../common.php");     
    
    if (empty($_POST)) { 
    	//Redirect to students.php
    	header("Location: students.php");
    	die("Redirecting to students page.");
    	
    }
    include 'Student.php';
    include '../../template/head.php';
    include '../../template/header.php';
    
    $student = new Student($_POST['student_id'], $db);
    ?>

	<section class='content'>
		<h1>Select programs for 
			<?php $student->printName()?>
		</h1>
		<section id='accordion'> 
			<form action='/math_plus/private/cart.php' method='post'>
				<input type='hidden' name='student_id' 
 					value=".$this->student_id."/>
				<?php $student->displayAllPrograms(); ?>
    			<input type='submit' value='Add programs to cart' />
    		</form>
		</section>
	</section>
	<?php include '../../template/footer.php';?>
</html>	


