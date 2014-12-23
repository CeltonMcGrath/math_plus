<?php 
    require("../common.php"); 
    include '../template/head.php';
    include '../template/header.php';    
?> 
	<section class="content">
		<?php
		
		foreach ($_POST as $key => $entry)
		{
			print $key . ": " . $entry . "<br>";
		}
		
		
		
		
	    if(isset($_SESSION["products"]))
	    {
	        $total = 0;
	        echo '<form method="post" action="PAYMENT-GATEWAY">';
	        echo '<ul>';
	        $cart_items = 0;
	        foreach ($_SESSION["products"] as $cart_itm)
	        {
	           $product_code = $cart_itm["code"];
	           $results = $mysqli->query("SELECT product_name,product_desc, price FROM products WHERE product_code='$product_code' LIMIT 1");
	           $obj = $results->fetch_object();
	           
	            echo '<li class="cart-itm">';
	            echo '<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["code"].'&return_url='.$current_url.'">&times;</a></span>';
	            echo '<div class="p-price">'.$currency.$obj->price.'</div>';
	            echo '<div class="product-info">';
	            echo '<h3>'.$obj->product_name.' (Code :'.$product_code.')</h3> ';
	            echo '<div class="p-qty">Qty : '.$cart_itm["qty"].'</div>';
	            echo '<div>'.$obj->product_desc.'</div>';
	            echo '</div>';
	            echo '</li>';
	            $subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
	            $total = ($total + $subtotal);
	
	            echo '<input type="hidden" name="item_name['.$cart_items.']" value="'.$obj->product_name.'" />';
	            echo '<input type="hidden" name="item_code['.$cart_items.']" value="'.$product_code.'" />';
	            echo '<input type="hidden" name="item_desc['.$cart_items.']" value="'.$obj->product_desc.'" />';
	            echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'" />';
	            $cart_items ++;
	            
	        }
	        echo '</ul>';
	        echo '<span class="check-out-txt">';
	        echo '<strong>Total : '.$currency.$total.'</strong>  ';
	        echo '</span>';
	        echo '</form>';
	        
	    }else{
	        echo 'Your cart is empty.';
	    }
?>
		
	</section>
	<?php include '../template/footer.php';?>
</html>