<?php 
    require("../library/common.php"); 

    unset($_SESSION['cart_programs']);
    
    include '../library/site_template/head.php';
    include '../library/site_template/header.php';
    echo "
    <section class='content'>
    	<h1>
    		Click the button below to pay and 
    		complete your registration.
    	</h1>
	    <form action='checkout.php' METHOD='POST'>
			<input type='hidden' name='cart_total' 
    			value=".$_POST['cart_total']." />
			<input type='image' name='paypal_submit' id='paypal_submit'
	        	src='https://www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif'
	        	border='0' align='top' alt='Pay with PayPal'/>
		</form>
    </section>";
    include '../library/site_template/footer.php';
    ?>
    
    
    <!-- Add Digital goods in-context experience.  -->
	<script 
		src='https://www.paypalobjects.com/js/external/dg.js' 
		type='text/javascript'>
	</script>
	<script>
		var dg = new PAYPAL.apps.DGFlow(
		{
			trigger: 'paypal_submit',
			expType: 'instant'
		});
	</script>
	
	<script>
		alert("Payment Cancelled"); 
		 // add relevant message above or remove the line if not required
		window.onload = function(){
			 if(window.opener){
				 window.close();
			} 
			 else{
				 if(top.dg.isOpen() == true){
		              top.dg.closeFlow();
		              return true;
		          }
		     }                              
		};                             
	</script>
	
</html>
