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
	   	$query = "SELECT user_id, first_name, last_name, phone_1, phone_2, email
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
	   	
		$this->user_id = $row['user_id'];
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

   
   /* Updates guardian data and returns true iff success. */
	public static function updateGuardian($g_id, $tel_1, $tel_2, $em, $db) {		
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


    /* Deletes guardian from database for guardians.php*/
	public static function deleteGuardian($g_id, $db) {
   		
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
      
    /* Returns the name of this guardian - FirstName LastName. */
	public function getName() {
		return $this->first_name." ".$this->last_name;
	}

	/*Returns the id of this guardian */
	public function getId() {
		return $this->guardian_id;
	}
	
	/*Returns the id of this guardian */
	public function getId() {
		return $this->guardian_id;
	}
	
	/*Returns the id of this guardian */
	public function getFirstName() {
		return $this->first_name;
	}
	
	/*Returns the id of this guardian */
	public function getLastName() {
		return $this->last_name;
	}
	
	/*Returns the id of this guardian */
	public function getPrimaryPhone() {
		return $this->phone_1;
	}
	
	/*Returns the id of this guardian */
	public function getSecondPhone() {
		return $this->phone_2;
	}
	
	/*Returns the id of this guardian */
	public function getEmail() {
		return $this->email;
	}
	
	
}

?>
