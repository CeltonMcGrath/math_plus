<?php  
    require("../../common.php");     
    include 'add_student.php';
    include '../../template/header.php'
?> 
    
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/contacts.css" />
	</head>
	<body>
		<h1>Students</h1>
			<section id="accordion">
				<div class="contact">
    				<input type="checkbox" id="check-0" />
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
		         
		        try 
		        { 
		            // Execute the query against the database 
		            $stmt = $db->prepare($query); 
		            $result = $stmt->execute($query_params); 
		        } 
		        catch(PDOException $ex) 
		        {   
		            die("Failed to run query: " . $ex->getMessage()); 
		        } 
		        $rows = $stmt->fetchAll();
		        
				foreach($rows as $row): ?> 
					<div class="contact">
	    				<input type="checkbox" id="<?php echo $row['student_id']?>" />
	    				<label for="<?php echo $row['student_id']?>"><?php echo $row['last_name']?>, <?php echo $row['first_name']?></label>
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
							    Delete: <input type="radio" name="delete" value="yes"/> Yes
							    <input type="radio" name="delete" value="no" checked/> No         
							    <br />
							    <input type="submit" value="Submit Changes" />
						</form>
	    				</article>
	    			</div>					
			    <?php endforeach; ?>
			</section>
	</body>
</html>

    