<?php  
    require("../library/common.php");     
    include '../library/Student.php';
    include '../library/forms/html_Generator.php';

    $hg = new html_Generator();
    
    if (empty($_POST)) { 
    	//Redirect to students.php
    	header("Location: students.php");
    	die("Redirecting to students page.");
    }
    
    $student = new Student($_POST['student_id'], $db);
?>

<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
	<div class="container">
		<h1>Select programs for <?php echo $student->getName() ?></h1>
		<div class="accordion" id="accordion">
			<form action='cart.php' method='post'>
				<input type='hidden' name='operation'
					value='update_student' />
				<input type='hidden' name='student_id' 
 					value= '<?php echo $student->getId()?>' />
 					
 				<?php 	
		 		//Select all upcoming programs
		 		$query = 'SELECT *
			   			FROM
		 					programs
			   			WHERE
		 					NOW() < programs.registration_deadline';
		 		try {
		 			$stmt = $db->prepare($query);
		 			$result = $stmt->execute();
		 		} catch(PDOException $ex) {
		 			error_log($ex->getMessage());
		 		}	
		 		$programRows = $stmt->fetchAll();
		
		 		if (count($programRows)==0) {
					echo "<h3>No upcoming programs. Check back again!</h3>";
				}
				else {
					foreach($programRows as $programRow):
						$program = new Program($programRow['program_id'], $db);
						//ID
						$id = $program->program_id;
						//Label
						if ($student->inProgram($program->program_id)) {
							$label = $program->displayLabelForSelectionTwo();
						}
						elseif ($program->remainingSpots() == 0) {
							$label = $program->displayLabelForSelectionThree();
						}
						else {
							$label = $program->displayLabelForSelectionOne();
						}
						// Article
						$article = $program->displayArticle();
							
						echo $hg->bootstrapAccordion($id, $label, $article);
					endforeach;
				}
 		?>
    			<input type='submit' value='Add programs to cart' />
    		</form>	
	</div>
  </body>
</html>


