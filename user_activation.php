<?php
/*Code adapted from http://www.9lessons.info/2013/11/php-email-verification-script.html 11/26/14 for PDO connection.*/

require('common.php');

$msg='';

if(!empty($_GET['code']) && isset($_GET['code']))
{
	$activation = $_GET['code'];
	
	$query = "
            SELECT
                uid
            FROM users
            WHERE
                activation = :activation
				and status = '0'
        	";
	
	$query_params = array(':activation' => $activation);
	
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
		$query = "UPDATE users SET status='1' WHERE activation= :activation";
		$query_params = array(':activation' => $activation);
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
		$msg="Your account is activated"; 
		
	}
	else
	{
		$msg ="An error occured.";
	}
	
}
?>

<?php echo $msg; ?>