<?php 
    require("../common.php"); 
    include '../template/header.php';
?> 
<html>
	<body>
		Hello <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>, secret content!<br /> 
		<a href="guardian_registration/guardians.php">Manage guardian and parent contacts</a><br /> 
		<a href="student_registration/students.php">Manage students and programs</a><br />
	</body>
</html>
