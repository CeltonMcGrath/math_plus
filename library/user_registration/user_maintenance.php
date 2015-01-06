<?php 
	function resetPassword ($email, $db) {
		/*Returns true iff password successfully reset and email sent.*/
		$temp = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $temp . $salt);
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		 
		$query_params = array(
			':password' => $password,
			':salt' => $salt,
			':email' => $_POST['email']
		);
		 
		$query = "
			UPDATE users 
			SET password = :password, salt = :salt 
			WHERE email = :email
		";

		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex)
		{
			return False;
		}		
		include 'send_mail.php';
		return sendTemporaryPassword($email, $temp);
	}

	function updatePassword ($user_id, $newPassword, $db) {
		/* Returns true iff password updated.*/
		
		//Generate new salt and hashed password
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $newPassword . $salt);
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
				 
		// Initial query parameter values
		$query = "UPDATE users
				SET password = :password, salt = :salt
				WHERE user_id = :user_id";
		$query_params = array(
			':password' => $password,
			':salt' => $salt,
			':user_id' => $user_id
		);
		
		try	{
			// Execute the query
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
			return False;
		}

		return True;
	}
	
	function updateEmail ($user_id, $email, $db) {
		/* Returns true iff users email is updated and 
		 * activation email sent.*/
		
		// Generate new activation key
		$activation = hash('sha256', $email.time());
		
		$query = "UPDATE users 
				SET email = :email, activation = :activation, status = '0'
				WHERE user_id = :user_id";
		$query_params = array(
			':email' => $email,
			':activation' => $activation,
			':user_id' => $user_id
		);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}	catch(PDOException $ex) {
			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
			return False;
		}
		
		// Send activation email
		include 'send_mail.php';
		return sendActivationEmail($email, $activation, "update");
	}
	
	function correctPassword($user_id, $test_password, $db) {
		/* Returns true iff password is correct. */
		
		return True;
	}
	
	function updateListserv($user_id, $listserv, $db) {
		/* Returns true iff users listserv setting updated in database. */
		$query = "UPDATE users
				SET listserv = :listserv
				WHERE user_id = :user_id";
		$query_params = array(
				':listserv' => $listserv,
				':user_id' => $user_id
		);
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
			return False;
		}
		return True;
	}

?> 

