<?php 
    require("../library/common.php"); 
    include '../library/config.php';
    include '../library/Form_Validator.php';
    include '../library/forms/html_Generator.php';
    include '../library/forms/Form_Generator.php';
    include '../library/Guardian.php';
       
    $fg = new Form_Generator();
    $hg = new html_Generator();
    
    $error = '';
    $success = '';
    // Check if form has been submitted
    if(!empty($_POST)) {
    	$form_validator = new Form_Validator();
    	$result = $form_validator->validateGuardianPOST($_POST);
    	if ($result!= -1) {
    		$error = $result;
    	}
    	else {
    		$data = $form_validator->sanitizeGuardianPost($_POST);
    		if ($data['guardian_id']==0) {
    			/* 0 indicates new guardian request. */
    			Guardian::createGuardian($_SESSION['user']['user_id'],
    			$data['first_name'], $data['last_name'],
    			$data['phone_1'], $data['phone_2'],
    			$data['email'], $db);
    			$success = "Guardian successfully added.";
    		}
    		else {
    			/* Update or delete guardian contact */
    			if ($data['delete']=="yes") {
    				Guardian::deleteGuardian($_POST['guardian_id'], $db);
    				$success = "Guardian deleted.";
    			}
    			else {
    				Guardian::updateGuardian($data['guardian_id'], 
    					$data['phone_1'], $data['phone_2'], $data['email'], 
    					$db);
    				$success = "Guardian successfully updated.";
    			}
    		}	
    	}    	
    }
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
?> 
	<section class="content">
		<h1>Guardians</h1>
			<span class='error'><?php echo $error ?></span>
			<span class='success'><?php echo $success ?></span>
			<section id="accordion">	
			<?php 
			//Display accordion boorm
				echo $hg->accordionBox(0, "Add new guardian contact", 
					$fg->guardianForm(0, '', '', '', '', ''));
			//Display accordion box for each registered guardian
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
					$label = $guardian->getName();
					$article = $hg->accordionBox($guardian->getId(), 
						$guardian->getFirstName(), $guardian->getLastName(), 
						$guardian->getPrimaryPhone(), $guardian->getSecondPhone(), 
						$guardian->getEmail());
					echo $hg->accordionBox($guardian->guardian_id, $label, $article);
			    endforeach; ?>
			</section>
	</section>
	<?php include '../library/site_template/footer.php';?>
</html>

