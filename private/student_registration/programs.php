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

	<section id=content>
		<h1>Select programs for 
			<?php $student->printName()?>
		</h1>
		<section id="accordion"> 
			<?php 
			//Get programs for student
    			$student->displayAllPrograms();
    		?>
		</section>
	</section>
	<?php include '../../template/footer.php';?>
</html>	


