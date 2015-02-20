<?php 
    require("../library/common.php"); 
    include '../library/Form_Validator.php';
    include '../library/forms/html_Generator.php';
    include '../library/Cart.php';
    
    $hg = new html_Generator();   
    $error = '';
    $success = '';
	
    $text_field = $GLOBALS['text_field'];
    
	if (!empty($_POST)) {
		$cart = new Cart($_SESSION['user']['user_id'], $db);
		$contents = $cart->getFormattedContents();
		$cost = Cart::getTotal($contents);
		if ($cost == 0) {
			$transaction_id = $cart->saveEmptyTransaction();
			$cart->closeTransaction($transaction_id);
			$success = $text_field['registration_success'];
		}
		else {
			// Prepare transaction data
			$fv = new Form_Validator();
			$result = $fv->validateTransactionPost($_POST);
			$data = $fv->sanitizeTransactionDetails($_POST);
			// Save transaction information
			
			$transaction_id = $cart->saveTransaction($data);
			// Check if transaction successful
			if ($result != -1) {
				$error = $result;
			}
			else {
				$cart->closeTransaction($transaction_id);
				$success = $text_field['registration_success'];
			}
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
		?>
      </div>
    </div>
  </body>
</html>

