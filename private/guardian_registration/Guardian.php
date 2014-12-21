<?php
class Guardian {
	
   private $guardian_id;
   private $user_id;
   private $first_name;
   private $last_name;
   private $phone_1;
   private $phone_2;
   private $email;
   private $database;    
 
   public function __construct($g_id, $db) {
   		$this->guardian_id = $g_id;
   		$this->database = $db;
   		
   		/*Retrieves guardian data from db.*/
	   	$query = "SELECT first_name, last_name, phone_1, phone_2, email
	    		FROM guardians
	    		WHERE guardian_id = :guardian_id";
	   	
	   	$query_params = array(':guardian_id' => $this->guardian_id);
	   	 
	   	try {
	   		// Execute the query against the database
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		return False;
	   	}
	   	$row = $stmt->fetch();
	   	
	   	$this->first_name = $row['first_name']; 
	   	$this->last_name = $row['last_name']; 
	   	$this->phone_1 = $row['phone_1']; 
	   	$this->phone_2 = $row['phone_2']; 
	   	$this->email = $row['email']; 
   }

   public static function createGuardian($u_id, $f_name,
   		$l_name, $tel_1, $tel_2, $em, $db) {
   	    /*Creates guardian contact in database.*/
   		
   		$query = "INSERT INTO guardians 
   				(user_id, first_name, last_name, phone_1, phone_2, email) 
	   			VALUES(
   					:user_id, :first_name, :last_name, 
   					:phone_1, :phone_2, :email
   				)";
	   	 
	   	$query_params = array(
	   			':user_id' => $u_id,
	   			':first_name' => $f_name,
	   			':last_name' => $l_name,
	   			':phone_1' => $tel_1,
	   			':phone_2' => $tel_2,
	   			':email' => $em
	   	);
	   	
	   	try	{
	   		// Execute the query against the database
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		return False;
	   	}
	   	return True;
   }
   
   public function displayGuardianForm() {
   	/* Display html form with student data. */
   	echo "
   		<div class='contact'>
   			<input class='accordion' type='checkbox' id='$this->guardian_id'
   					/>
   			<label for=".$this->guardian_id.">
   					".$this->last_name.", ".$this->first_name."
   			</label>
   			<article>
   		    	<form action='guardians.php' method='post'/>
   		    		<input type='hidden' name='guardian_id'
   							value='$this->guardian_id'/>
   					Primary phone: <input type='tel' name='phone_1'
   							value='$this->phone_1'/>
   					<br />
   					Secondary phone: <input type='tel' name='phone_2'
   							value='$this->phone_2'/>
   					<br />
   					Email: <input type='text' name='email'
   							value='$this->email'/>
   					<br />
   					Delete:
   					<input type='radio' name='delete'
   							value='yes'/> Yes
   					<input type='radio' name='delete' value='no' checked/> No
   					<br />
   					<input type='submit' value='Submit changes'
   							name='update' />
   				</form>
   		    </article>
   		 </div>";
   }
   
	public static function updateGuardian($g_id, $tel_1, $tel_2, $em, $db) {
		/* Updates guardian data and returns true iff success. */
		$query = "UPDATE guardians
	    		SET phone_1 = :phone_1, phone_2 = :phone_2, email = :email
	    		WHERE guardian_id = :guardian_id";
		
		$query_params = array (
				':phone_1' => $tel_1,
				':phone_2' => $tel_2,
				':email' => $em,
				':guardian_id' => $g_id 
		);
		
		try {
			// Execute the query against the database
			$stmt = $db->prepare ( $query );
			$result = $stmt->execute ( $query_params );
		} catch ( PDOException $ex ) {
			return False;
		}
		return True;
	}


      	
   public static function deleteGuardian($g_id, $db) {
   		/* Deletes guardian from database.*/
	   	$query = "DELETE FROM guardians
	    		WHERE guardian_id = :guardian_id";
	   	
	   	$query_params = array(':guardian_id' => $g_id);
	   	
	   	try {
	   		// Execute the query against the database
	   		$stmt = $db->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex)	{
	   		echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
	   	}
	   	return True;
   }
   
   static function displayEmptyGuardianForm() {
   		/* Displays html form for guardian to created. */
   		echo "
   		<div class='contact'>
   			<input class='accordion' type='checkbox' id='0' />
   			<label for='0'>Add new contact</label>
   			<article>
   		    	<form action='guardians.php' method='post'/>
   		    		<input type='hidden' name='guardian_id' value='0' />
   					First name: <input type='text' name='first_name' value=''/>
   					<br />
   					Last name: <input type='text' name='last_name' value=''/>
   					<br />
   					Primary phone: <input type='tel' name='phone_1' value=''/>
   					<br />
   					Secondary phone: <input type='tel' name='phone_2' value=''/>
   					<br />
   					Email: <input type='text' name='email' value=''/>
   					<br />
   					<input type='submit' value='Add contact' name='update' />
   				</form>
   		    </article>
   		 </div>";
   } 

}

?>