<?php  
    require("../../common.php");     
    include 'add_student.php';
    include '../../template/head.php';
    include '../../template/header.php';
    include '../../template/footer.php';
?> 
	<body>
		<h1>Students</h1>
			<section id="accordion">
				<div class="contact">
    				<input class="accordion" type="checkbox" id="check-0" />
    				<label for="check-0">Add new student</label>
    				<article>
    					<form action="students.php" method="post"> 
    						<input type="hidden" name="student_id" value="0" />
				    		First name: <input type="text" name="first_name" value="First name"/> 
						    <br />
						    Last name: <input type="text" name="last_name" value="Last name"/> 
						    <br />
						    Preferred name:<input type="text" name="preferred_name" value="Preferred name"/> 
						    <br />
						    Grade:<input type="text" name="grade" value="Grade"/> 
						    <br />
						    Allergies:<input type="text" name="allergies" value="Allergies"/> 
						    <br />
						    Medical:<input type="text" name="medical" value="Medical"/>
						    <br /> 
						    Photo permission: 
						    <input type="radio" name="photo_permission" value="yes" checked>Yes
						    <input type="radio" name="photo_permission" value="no">No
						    <br />
						    <input type="submit" value="Submit" />
						</form> 
    				</article>
    			</div>
			<?php // Generate accordian-style contact list

				// Query the db for guardian contacts associated with current user
				$query = "SELECT student_id, first_name, last_name, preferred_name, grade, allergies, medical
    						FROM students
    						WHERE user_id = :user_id"; 
         
		        $query_params = array( 
		            ':user_id' => $_SESSION['user']['user_id']
		        ); 
		         
		        try { 
		            // Execute the query against the database 
		            $stmt = $db->prepare($query); 
		            $result = $stmt->execute($query_params); 
		        } 
		        catch(PDOException $ex) {   
		            die("Failed to run query: " . $ex->getMessage()); 
		        } 
		        $rows = $stmt->fetchAll();
		        
				foreach($rows as $row): ?> 
				<div class="contact">
    				<input class="accordion" type="checkbox" id="<?php echo $row['student_id']?>" />
    				<label for="<?php echo $row['student_id']?>">
    					<?php echo $row['last_name']?>, <?php echo $row['first_name']?>
    				</label>
    				<article>
    					<form action="students.php" method="post"> 
    						<input type="hidden" name="student_id" value="<?php echo $row['student_id']?>" />
						    Preferred name:<input type="text" name="preferred_name" value="<?php echo $row['preferred_name']?>"/> 
						    <br />
						    Grade:<input type="text" name="grade" value="<?php echo $row['grade']?>"/> 
						    <br />
						    Allergies:<input type="text" name="allergies" value="<?php echo $row['allergies']?>"/> 
						    <br />
						    Medical:<input type="text" name="medical" value="<?php echo $row['medical']?>"/>
						    <br /> 
						    Photo permission: 
					    	<input type="radio" name="photo_permission" value="yes" checked>Yes
					   		<input type="radio" name="photo_permission" value="no">No
					    	<br />
					    	
					    	<br />
						    Delete student: <input type="radio" name="delete" value="yes"/> Yes
						    <input type="radio" name="delete" value="no" checked/> No         
						    <br />
						    <input type="submit" value="Submit Changes" />
						</form>
						<br />
						<form action="programs.php" method="post">
							<input type="hidden" name="student_id" value="<?php echo $row['student_id']?>" />
							Upcoming programs: <ul>
								<?php // Generate list of upcoming programs student is registered in.
								// Query the db for guardian contacts associated with current user
								$query2 = "SELECT programs.program_name, programs.start_date, programs.end_date, 
											students_programs.status 
											FROM students_programs INNER JOIN programs
											on students_programs.program_id = programs.program_id
				    						WHERE student_id = :student_id"; 
				         
						        $query_params2 = array( 
						            ':student_id' => $row['student_id']
						        ); 
						         
						        try { 
						            // Execute the query against the database 
						            $stmt2 = $db->prepare($query2); 
						            $result2 = $stmt2->execute($query_params2); 
						        } 
						        catch(PDOException $ex) {   
						            die("Failed to run query: " . $ex->getMessage()); 
						        } 
						        $rows2 = $stmt2->fetchAll();
						        
								foreach($rows2 as $row2): 
									echo "<li>".row2['program_name']."</li>";
								endforeach; ?>
							</ul> <br />
					    	<input type="submit" value="Add or view programs" />
					    </form>
    				</article>
    			</div>					
		    <?php endforeach; ?>
		</section>
	</body>
</html>

    