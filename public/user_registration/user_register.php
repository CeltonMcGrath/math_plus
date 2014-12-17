<?php 
	function userExists($email, $db) {
		/*Returns True iff the user exists in the database*/
		
		$query = "SELECT 1 FROM users WHERE email = :email";		
		$query_params = array(':email' => $email);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			// Do nothing
		}
		
		$row = $stmt->fetch();

		if($row) {
			return True;
		}
		else {
			return False;
		}
	}
	
	function addUser($email, $password, $db) {
		/*Returns True iff user is successfully added to the database 
		 * and activation email successfully sent.*/

		//Hash password
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $password . $salt);
		// Generate activation key
		$activation = hash('sha256', $email.time());
		// Hash password several more times
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		
		$query = "INSERT INTO users (email, password, salt,activation) VALUES
				(:email, :password, :salt, :activation)";		
		$query_params = array(
			':email' => $email,
			':password' => $password,
			':salt' => $salt,
			':activation' => $activation
		);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} 
		catch(PDOException $ex) {
			return False;
		}
		
		include 'send_mail.php';
		return sendActivationEmail($email, $activation, "new user");
	}
	


?> 

