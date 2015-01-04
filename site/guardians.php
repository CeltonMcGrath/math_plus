<?php 
    require("../library/common.php");     
    include '../library/Guardian.php';
       
    // Check if form has been submitted
    if(!empty($_POST)) {
	//Validate user input
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$error = "Please enter a valid email.";
	}
	elseif () {
		$error = "Please enter a numeric telephone number, including 
			area code.";
	}
    	elseif ($_POST['guardian_id']==0) {
    		/* 0 indicates new guardian request. */
		Guardian::createGuardian($_SESSION['user']['user_id'],
    		$f_name = $_POST['first_name'], $l_name = $_POST['last_name'],
    		$tel_1 = $_POST['phone_1'], $tel_2 = $_POST['phone_2'],
    		$em = $_POST['email'], $db);
    	}
    	else {
    		/* Update or delete guardian contact */
    		if(($_POST['delete'])=="yes") {
    			Guardian::deleteGuardian($_POST['guardian_id'], $db);
    		}
    		else {
    			Guardian::updateGuardian($_POST['guardian_id'], 
    				$_POST['phone_1'], $_POST['phone_2'], $_POST['email'], $db);
    		}
    	}
    }
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?> 
	<section class="content">
		<h1>Guardians</h1>
			<section id="accordion">
			<?php 
			Guardian::displayEmptyGuardianForm();
			// Generate accordian-style contact list
				// Query the db for guardian contacts 
				// associated with current user
				$query = "SELECT guardian_id FROM guardians 
    				WHERE user_id = :user_id"; 
		        $query_params = array( 
		            ':user_id' => $_SESSION['user']['user_id']
		        ); 
		         
		        try { 
		            $stmt = $db->prepare($query); 
		            $result = $stmt->execute($query_params); 
		        } 
		        catch(PDOException $ex)  {   
		            die("Failed to run query: " . $ex->getMessage()); 
		        } 
		        $rows = $stmt->fetchAll();
		        
				foreach($rows as $row):
					$guardian = new Guardian($row['guardian_id'], $db);
					$guardian->displayGuardianForm();					
			    endforeach; ?>
			</section>
	</section>
	<?php include '../library/site_template/footer.php';?>
</html>

