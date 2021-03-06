<?php 
    // These variables define the connection information for your MySQL database 
    $username = "root"; 
    $password = "sp2014"; 
    $host = "localhost"; 
    $dbname = "login_system"; 
        
    $public_area = array("login", "forgot_password", "register", "registration_terms", "user_activation");

    // Communicate with the database via UTF-8 
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
     
	// Connect to database.
    try { 
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", 
        	$username, $password, $options); 
    } 
    catch(PDOException $ex) { 
        // TO DO : what to do with die statements? 
        die("Failed to connect to the database: " . $ex->getMessage()); 
    } 
     
    // Allow the use of try catch blocks 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
    // Use string indexes for array
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
     
    
    // This block of code is used to undo magic quotes.  Magic quotes are a terrible 
    // feature that was removed from PHP as of PHP 5.4.  However, older installations 
    // of PHP may still have magic quotes enabled and this code is necessary to 
    // prevent them from causing problems.  For more information on magic quotes: 
    // http://php.net/manual/en/security.magicquotes.php 
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    { 
        function undo_magic_quotes_gpc(&$array) 
        { 
            foreach($array as &$value) 
            { 
                if(is_array($value)) 
                { 
                    undo_magic_quotes_gpc($value); 
                } 
                else 
                { 
                    $value = stripslashes($value); 
                } 
            } 
        } 
     
        undo_magic_quotes_gpc($_POST); 
        undo_magic_quotes_gpc($_GET); 
        undo_magic_quotes_gpc($_COOKIE); 
    } 
    
    include 'config.php';
    
    header('Content-Type: text/html; charset=utf-8'); 
     
    session_start(); 
    
    /* If the user is not logged in and is trying to access private user area, 
     * redirect to login.
     */
    if(empty($_SESSION['user'])) {
    	if (!preg_match('/'.$public_area[0].'/', $_SERVER['REQUEST_URI']) &
    		!preg_match('/'.$public_area[1].'/', $_SERVER['REQUEST_URI']) &
    		!preg_match('/'.$public_area[2].'/', $_SERVER['REQUEST_URI'])
		& !preg_match('/'.$public_area[3].'/', $_SERVER['REQUEST_URI']) 
    	 	& !preg_match('/'.$public_area[4].'/', $_SERVER['REQUEST_URI']) ) {
    			header("Location: login.php");
    			die("Redirecting to login page.");
    	}
    }
    /*// If the user is logged in and is trying to access login, logout or forgot password page, redirect to splash.
    elseif (!empty($_SESSION['user']) && substr($_SERVER['REQUEST_URI'], 0, strlen($public_dir))==$public_dir) {
    	header("Location: "/math_plus/site/splash.php");
    	die("Redirecting to the homepage.");
    }*/
