<?php 
    require("../library/common.php"); 
    include '../library/Form_Validator.php';
    
	if (!empty($_POST)) {
		//First test to see if post came from $0.00 transaction.
		
		//Otherwise, process Moneris transaction.
		$fv = new Form_Validator();
		$data = $fv->sanitizeTransactionDetails($_POST);
		//Test respnse codes from data... may need to redirect early.		
		$cart = new Cart($_SESSION['user']['user_id'], $db);
		$content = cart->registerStudents($data);		
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
        <h3>Your registration was a success.</h3>
        <?php echo $content?>
      </div>
    </div>
    <?php include '../library/site_template/body_end.php' ?>
  </body>
</html>

