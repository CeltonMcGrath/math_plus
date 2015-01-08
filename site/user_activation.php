<?php
/*Code adapted from http://www.9lessons.info/2013/11/php-email-verification-script.html 11/26/14 for PDO connection.*/

	require('../library/common.php');
	$msg='';

	if(!empty($_GET['activation']) && isset($_GET['activation'])) {
		$activation = $_GET['activation'];
		
		$query = "SELECT email
	            FROM users
	            WHERE activation = :activation and status = '0'";
		$query_params = array(':activation' => $activation);
		
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params); 
		}
		catch(PDOException $ex) {
			die("Activation unsuccessful");
		}
		
		$row = $stmt->fetch();
		
		if($row) {
			$query = "UPDATE users SET status='1' WHERE activation= :activation";
			$query_params = array(':activation' => $activation);
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex) {
				echo("<script>console.log('PHP: ".$ex->getMessage()."');
	   				</script>");
			}
			$msg="Your account is now activated. Click <a href='login.php'>here</a> to login."; 	
		}
		else{
			$msg ="Activation unsuccessful.";
		}
	}
?>

<?php echo $msg; ?>