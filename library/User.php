<?php
class User {
	
   private $user_id;
   private $email;
   private $hashedPassword;
   private $salt;
   private $listserv;
   private $status;  
 
   public function __construct($input, $db) {
   		$this->database = $db;
		if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
   			$this->email = $input;
   			$query = "SELECT user_id, password, salt, listserv, status
	    		FROM users
	    		WHERE email = :email";  			 
   			$query_params = array(':email' => $this->email);  			
   		}
   		else {
   			$this->user_id = $input;
   			$query = "SELECT email, password, salt, listserv, status
	    		FROM users
	    		WHERE user_id = :user_id";
   			$query_params = array(':user_id' => $input);
   		}
   	
	   	try {
	   		$stmt = $this->database->prepare($query);
	   		$result = $stmt->execute($query_params);
	   	}
	   	catch(PDOException $ex) {
	   		error_log($ex->getMessage());
	   	}
	   	$row = $stmt->fetch();
	   	
	   	if (!isset($this->email)) {
	   		$this->email = $row['email'];
	   	}
	   	else {
	   		$this->user_id = $row['user_id'];
	   	}
		
	   	$this->hashedPassword = $row['password']; 
	   	$this->salt = $row['salt'];
	   	$this->listserv = $row['listserv']; 
	   	$this->status = $row['status']; 
   }

   public static function createUser($em, $pw, $list, $db) {
   	    /*Returns True iff user is successfully added to the database 
		 * and activation email successfully sent.*/
		
		//Hash password
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $password . $salt);
			// Hash password several more times
			for($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}
		// Generate activation key
		$activation = hash('sha256', $email.time());

		
		$query = "INSERT INTO users 
					(email, password, salt, activation, listserv) 
				VALUES
					(:email, :password, :salt, :activation, :listserv)";
				
		$query_params = array(
			':email' => $email,
			':password' => $password,
			':salt' => $salt,
			':activation' => $activation,
			':listserv' => $listserv
		);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} 
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
		
		include 'send_mail.php';
		return sendActivationEmail($email, $activation, "new user");
	}
	
	/* Updates users password */
	public function updatePassword($newPassword) {
		//Generate new salt and hashed password
		$newSalt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$newHashedPassword = hash('sha256', $newPassword . $newSalt);
			for($round = 0; $round < 65536; $round++) {
				$newHashedPassword = hash('sha256', $newHashedPassword . $newSalt);
			}
			
		// Initial query parameter values
		$query = "UPDATE users
				SET password = :password, salt = :salt
				WHERE user_id = :user_id";
		$query_params = array(
				':password' => $newHashedPassword,
				':salt' => $newSalt,
				':user_id' => $this->user_id
		);
		
		try	{
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
	}
		
	/* Updates users email, deactivates account and sends activation email. */
	public function updateEmail ($newEmail) {
		// Generate new activation key
		$newActivation = hash('sha256', $email.time());
		
		$query = "UPDATE users
				SET email = :email, activation = :activation, status = '0'
				WHERE user_id = :user_id";
		$query_params = array(
				':email' => $newEmail,
				':activation' => $newActivation,
				':user_id' => $this->user_id
		);
		
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}	
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
		
		// Send activation email
		include 'send_mail.php';
		return sendActivationEmail($email, $activation, "update");
	}
	
	/* Updates users lisetserv setting */
	public function updateListserv ($newSetting) {
		$query = "UPDATE users
				SET listserv = :listserv
				WHERE user_id = :user_id";
		$query_params = array(
				':listserv' => $newSetting,
				':user_id' => $this->user_id
		);
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
	}
	
	/* Returns true iff password is correct. */
	public function correctPassword($testPassword) {
		$testHashedPassword = hash('sha256', $testPassword.$this->salt);
			for($round = 0; $round < 65536; $round++) {
				$testHashedPassword =
					hash('sha256', $testHashedPassword.$this->salt);
			}
		return $this->hashedPassword === $testHashedPassword;
	}
	
	/* Resets users password and sends temporary password email. */
	public function resetPassword () {
		$temp = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $temp . $salt);
			for($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}
		
		$this->salt = $salt;
		$this->hashedPassword = $password;
			
		$query_params = array(
				':password' => $this->hashedPassword,
				':salt' => $this->salt,
				':email' => $this->email
		);	
		$query = "
			UPDATE users
			SET password = :password, salt = :salt
			WHERE email = :email
		";
	
		try {
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
		include 'send_mail.php';
		return sendTemporaryPassword($this->email, $temp);
	}
	
	/*Returns True iff the user exists in the database*/
	public static function userExists($email, $db) {
		$query = "SELECT 1 FROM users WHERE email = :email";
		$query_params = array(':email' => $email);
	
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			error_log($ex->getMessage());
		}
	
		$row = $stmt->fetch();
	
		if($row) {
			return True;
		}
		else {
			return False;
		}
	}

	public function getId() {
		return $this->user_id;
	}

	public function getStatus() {
		return $this->status;
	}
	
	public function getListserv() {
		return $this->listserv;
	}	
}

?>
