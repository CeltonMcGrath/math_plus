<?php 
    require("../../common.php");     
    include 'add_guardian.php';
    include '../../template/header.php'
?> 

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../css/contacts.css" />
	</head>
	<body>
		<h1>Guardians</h1>
			<section id="accordion">
				<div class="contact">
    				<input type="checkbox" id="check-0" />
    				<label for="check-0">Add new contact</label>
    				<article>
    					<form action="guardians.php" method="post"> 
    						<input type="hidden" name="guardian_id" value="0" />
				    		<input type="text" name="first_name" placeholder="First name"/> 
						    <br />
						    <input type="text" name="last_name" placeholder="Last name"/> 
						    <br />
						    <input type="text" name="phone_1" placeholder="Primary phone number"/> 
						    <br />
						    <input type="text" name="phone_2" placeholder="Secondary phone number"/> 
						    <br />
						    <input type="text" name="email" placeholder="Email"/>
						    <br /> 
						    <input type="submit" value="Submit" />
						</form> 
    				</article>
    			</div>
			<?php // Generate accordian-style contact list

				// Query the db for guardian contacts associated with current user
				$query = "SELECT guardian_id, first_name, last_name, phone_1, phone_2, email
    						FROM guardians
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
	    				<input type="checkbox" id="<?php echo $row['guardian_id']?>" />
	    				<label for="<?php echo $row['guardian_id']?>"><?php echo $row['last_name']?>, <?php echo $row['first_name']?></label>
	    				<article>
	    					<form action="guardians.php" method="post"/>
	    						<input type="hidden" name="guardian_id" value="<?php echo $row['guardian_id']?>" />   
							    Primary phone: <input type="text" name="phone_1" value="<?php echo $row['phone_1']?>"/> 
							    <br />
							    Secondary phone: <input type="text" name="phone_2" value="<?php echo $row['phone_2']?>"/> 
							    <br />
							    Email: <input type="text" name="email" value="<?php echo $row['email']?>"/>
							    <br /> 
							    Delete: <input type="radio" name="delete" value="yes"/> Yes					    
							    <br />
							    <input type="submit" value="Submit changes" name="update" />
							</form>
	    				</article>
	    			</div>					
			    <?php endforeach; ?>
			</section>
	</body>
</html>

