<?php 
    require("../library/common.php"); 
	if (!empty($_POST)) {
		$order_id = $_POST['response_order_id'];
		$reponse_code = ;
		$date_stamp = ; // yyyy-mm-dd
		$time_stamp = ; // ##:##:##
		$bank_approval_code = ; #
		$result = ; // 1=approved, 0=declined/incomplete
		$trans_name = ; //purchase, preauth, cavv_purchase, cavv_preauth
		$cardholder = ; //cardholdersname
		$charge_total = ; // (40) with two decimals
		$
		
		$cart = new Cart($_SESSION['user']['user_id'], $db);
		$cart->registerStudents($transactionId, $orderTime, $amt);
	}
	else {
		header("Location: cart.php");
		die("Redirecting to shopping cart.");
	}   
?>

<!DOCTYPE html>
<html lang="en">
  <?php include '../library/site_template/head_private_area.php' ?>
  <body>
	<?php include '../library/site_template/navbar.php' ?>   
    <div class="container">
      <div class="jumbotron">
        <h3>Successful program registration information here.</h3>
      </div>
    </div>
    <?php include '../library/site_template/body_end.php' ?>
  </body>
</html>

