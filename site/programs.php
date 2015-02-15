<?php  
    require("../library/common.php");     
    include '../library/Student.php';
    
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
				echo "
				<div class='jumbotron'>
					<h3>No upcoming programs. Check back again!</h3>
				</div>";
			}
			else {
				echo "
  				<form action='cart.php' method='post'>
					<input type='hidden' name='student_id' 
	 					value= '".$student->getId()."' />	
	 				<ul class='list-group'>
		    			<h3 class='list-group-item'>
		  					Select programs for ".$student->getName()."
						</h3>";
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
						
					echo
						"<li class='list-group-item'>
							<div class='panel panel-default'>
								<div class='panel-heading'>$label</div>
								<div class='panel-body'>
									$article
								</div>
							</div>
						</li>";
				endforeach;
				echo "
						<li class='list-group-item'>
			    			<button type='submit' id='submit' 
			    				name='update' 
					    		class='btn btn-md btn-primary btn-block'' 
					    		value='submit'>Add programs to cart
					    	</button>
						</li>
					</ul>
		    	</form>";
			}
 		?>
 	</div>		
  </body>
</html>


