<?php 
    require("common.php"); 
?> 

<?php include 'template/header.php'?>

<html>
	<body>
		Hello <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>, secret content!<br /> 
		<a href="splash/guardian_registration/guardians.php">Manage guardian and parent contacts</a><br /> 
		<a href="splash/student_registration/students.php">Manage students and programs</a><br />
	</body>
</html>
