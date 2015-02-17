<?php 
    require("../library/common.php"); 
    include '../library/Form_Validator.php';
    include '../library/forms/html_Generator.php';
    include '../library/Cart.php';
    
    
    $error = '';
    $success = '';
    $content = '';

	if (!empty($_POST)) {				
		// Prepare transaction data
		$fv = new Form_Validator();
		$result = $fv->validateTransactionPost($_POST);
		$data = $fv->sanitizeTransactionDetails($_POST);		
		// Save transaction information
		$cart = new Cart($_SESSION['user']['user_id'], $db);
		$transaction_id = $cart->saveTransaction($data);
		// Check if transaction successful		
		if ($result!= -1) {
			$error = $result;
		}
		else {
			$content = $cart->closeTransaction($transaction_id);
		}				
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
      	<?php 
			echo $hg->errorMessage($error);
			echo $hg->successMessage($success);		
			echo content; 
		?>
      </div>
    </div>
    <?php include '../library/site_template/body_end.php' ?>
  </body>
</html>

