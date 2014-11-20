<?php 
    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 

    // This if statement checks to determine whether the registration form has been submitted 
    // If it has, then the registration code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
    	$valid_id = true; 
    	 
        // Make sure the user entered a valid E-Mail address 
        // filter_var is a useful PHP function for validating form input, see: 
        // http://us.php.net/manual/en/function.filter-var.php 
        // http://us.php.net/manual/en/filter.filters.php 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            $emailErr = "Valid email is required";
            $valid_id = false;
        }  
        
        //Esnure that the user has entered matching email
        if ($_POST['email'] != $_POST['email2'])
        {
        	$email2Err = "Email does not match.";
        	$valid_id = false;
        }
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            $passwordErr = "Password is required"; 
            $valid_id = false;
        } 
        
        // Ensure that the user has entered the second password correctly
        if($_POST['password'] != $_POST['password2'])
        {
        	$password2Err = "Passwords do not match.";
        	$valid_id = false;
        }
        
        if($valid_id)
        {
        	// We will use this SQL query to see whether the email entered by the
        	// user is already in use.  A SELECT query is used to retrieve data from the database.
        	// :email is a special token, we will substitute a real value in its place when
        	// we execute the query.
        	$query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        	";
        	 
        	// This contains the definitions for any special tokens that we place in
        	// our SQL query.  In this case, we are defining a value for the token
        	// :email.  It is possible to insert $_POST['email'] directly into
        	// your $query string; however doing so is very insecure and opens your
        	// code up to SQL injection exploits.  Using tokens prevents this.
        	// For more information on SQL injections, see Wikipedia:
        	// http://en.wikipedia.org/wiki/SQL_Injection
        	$query_params = array(
        	':email' => $_POST['email']
        	);
        	 
        	try
        	{
        		// These two statements run the query against your database table.
        		$stmt = $db->prepare($query);
        		$result = $stmt->execute($query_params);
        	}
        	catch(PDOException $ex)
        	{
        		// Note: On a production website, you should not output $ex->getMessage().
        		// It may provide an attacker with helpful information about your code.
        		die("Failed to run query: " . $ex->getMessage());
        	}
        	 
        	// The fetch() method returns an array representing the "next" row from
        	// the selected results, or false if there are no more rows to fetch.
        	$row = $stmt->fetch();
        	 
        	// If a row was returned, then we know a matching email was found in
        	// the database already and we should not allow the user to continue.
        	if($row)
        	{
        		die("This email is already in use");
        	}
        	 
        	// An INSERT query is used to add new rows to a database table.
        	// Again, we are using special tokens (technically called parameters) to
        	// protect against SQL injection attacks.
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
        	 
        	// A salt is randomly generated here to protect again brute force attacks
        	// and rainbow table attacks.  The following statement generates a hex
        	// representation of an 8 byte salt.  Representing this in hex provides
        	// no additional security, but makes it easier for humans to read.
        	// For more information:
        	// http://en.wikipedia.org/wiki/Salt_%28cryptography%29
        	// http://en.wikipedia.org/wiki/Brute-force_attack
        	// http://en.wikipedia.org/wiki/Rainbow_table
        	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        	 
        	// This hashes the password with the salt so that it can be stored securely
        	// in your database.  The output of this next statement is a 64 byte hex
        	// string representing the 32 byte sha256 hash of the password.  The original
        	// password cannot be recovered from the hash.  For more information:
        	// http://en.wikipedia.org/wiki/Cryptographic_hash_function
        	$password = hash('sha256', $_POST['password'] . $salt);
        	 
        	// Next we hash the hash value 65536 more times.  The purpose of this is to
        	// protect against brute force attacks.  Now an attacker must compute the hash 65537
        	// times for each guess they make against a password, whereas if the password
        	// were hashed only once the attacker would have been able to make 65537 different
        	// guesses in the same amount of time instead of only one.
        	for($round = 0; $round < 65536; $round++)
        	{
        	$password = hash('sha256', $password . $salt);
        	}
        	 
        	// Here we prepare our tokens for insertion into the SQL query.  We do not
        	// store the original password; only the hashed version of it.  We do store
        	// the salt (in its plaintext form; this is not a security risk).
        	$query_params = array(
        	':email' => $_POST['email'],
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
        			// Note: On a production website, you should not output $ex->getMessage().
        			// It may provide an attacker with helpful information about your code.
        			die("Failed to run query: " . $ex->getMessage());
        			}
        			 
        			// This redirects the user back to the login page after they register
        			header("Location: login.php");
        	 
        	// Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        	// will be sent to the user if you do not die or exit.
        	die("Redirecting to login.php");
        } 
        // If the login form has been submitted with some data, refill username form
        else 
        {
        	$emailEntry = $_POST['email'];
        }
       
    } 
    
    //$emailEntry = $emailErr = $email2Err = $passwordErr = $password2Err = "";
     
?> 

<h1>Register</h1> 
<form action="register.php" method="post"> 
	<span class="success"></span>
	<span class="error">*Required fields</span>
	<br><br />
	
    Email:<br /> 
    <input type="text" name="email" value="<?php echo $emailEntry;?>" /> 
    <span class="error">* <?php echo $emailErr;?></span>
    <br /><br /> 
    Re-enter your email:<br /> 
    <input type="text" name="email2" value="" /> 
    <span class="error">* <?php echo $email2Err;?></span>
    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /> 
    <span class="error">* <?php echo $passwordErr;?></span>
    <br /><br /> 
    Re-enter password:<br /> 
    <input type="password" name="password2" value="" /> 
    <span class="error">* <?php echo $password2Err;?></span>
    <br /><br />
    <input type="submit" value="Register" /> 
</form>