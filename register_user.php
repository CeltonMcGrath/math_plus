<?php 

	function userExists($email, $db) {
		/*Returns True iff the user exists in the database*/
		
		$query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        	";
		
		$query_params = array(':email' => $email);
		
		try
		{
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex)
		{
			// Note: On a production website, you should not output $ex->getMessage().
			// It may provide an attacker with helpful information about your code.
			die("Failed to run query: " . $ex->getMessage());
		}
		
		$row = $stmt->fetch();

		if($row)
		{
			return True;
		}
	}
	
	function addUser($email, $password, $db) {
		/*Returns True iff user is successfully added to the database.*/
				
		$query = "
            INSERT INTO users (
                email,
                password,
                salt
            ) VALUES (
                :email,
                :password,
                :salt
            )
        	";
		
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		
		$password = hash('sha256', $password . $salt);
		
		for($round = 0; $round < 65536; $round++)
		{
			$password = hash('sha256', $password . $salt);
		}
		
		// Here we prepare our tokens for insertion into the SQL query.  We do not
		// store the original password; only the hashed version of it.  We do store
		// the salt (in its plaintext form; this is not a security risk).
		$query_params = array(
		':email' => $email,
		':password' => $password,
		':salt' => $salt
		);
		
		try
		{
			// Execute the query to create the user
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} 
		catch(PDOException $ex)
		{
			return False;
		}
		
		return True;
	}


?> 

