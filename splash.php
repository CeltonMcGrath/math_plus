<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
    
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
?> 

<?php include 'template/header.php'?>

<html>
	<body>
		Hello <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>, secret content!<br /> 
		<a href="memberlist.php">Memberlist</a><br /> 
		<a href="splash/guardian_registration/guardians.php">Manage guardian and parent contacts</a><br /> 
		<a href="splash/student_registration/students.php">Manage students and programs</a><br />
	</body>
</html>
