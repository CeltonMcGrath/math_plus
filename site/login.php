<?php 
    require("../library/common.php"); 
    
    // Redisplay user email if they have a login email.
    $submitted_email = '';
    $login_error = '';
    $db_error = False;
    
    // Check if login form submitted
    if(!empty($_POST)) 
    { 
        // Get credentials from database 
        $query = "SELECT user_id, email, password, salt, status
            FROM users 
            WHERE email = :email"; 
        // The parameter values 
        $query_params = array( 
            ':email' => $_POST['email']
        ); 
         
        try { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) {  
            $db_error = True; 
        } 
         
        // Retrieve the user data from the database. If $row is false, then the email 
        // they entered is not registered. 
        if (!$db_error) {
        	$row = $stmt->fetch();
        }
        // If the user exists, then validate user data. Otherwise, reject login.
        if($row) { 
            // Check if password match.
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++) { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            }              
            if($check_password === $row['password'] && $row['status'] == 1) { 
            	// Login successful.
            	// Only store user id and email in session variable.
                unset($row['salt']); 
	            unset($row['password']); 
	            unset($row['status']);
	            $_SESSION['user'] = $row; 
	             
	            // Redirect the user to the private members-only page. 
	            header("Location: splash.php"); 
	            die("Logging in..."); 
            } 
            elseif ($row['status'] == 0) {
            	$login_error = "Account not activated.";
            }
            else {
            	$login_error = "Incorrect username and/or password.";
            }
        } 
        else {
        	$login_error = "Incorrect username and/or password.";
        } 
        $submitted_email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); 
    }    
?> 

<html>
	<head>
		<meta charset="utf-8">
		<title>Math+ Registration</title>
		<style type="text/css">
			@import url(../public_html/style.css);
			@import url(../public_html/main.css);
		</style>
		<link rel="icon" href="../public_html/favicon.ico" type="image/x-icon">
	</head>
	
	<body>
		<section class="container">
			<div class="login">
				<h1>Login</h1> 
				<span class="error"><?php echo $login_error;?></span>
				<br /><br />
				<form action="login.php" method="post">
				    Email:<br /> 
				    <input type="email" name="email" 
				    	value="<?php echo $submitted_email; ?>" />
				    <br /><br /> 
				    Password:<br /> 
				    <input type="password" name="password" value="" /> 
				    <br /><br /> 
				    <input type="submit" value="Login" />
				</form> 				
			</div>
			<div class="login-extra">
				<a href="register.php">Register</a>
				<a href="forgot_password.php">Forgot password</a>
			</div>
		</section>
	</body>
</html>
