<?php
/* =====================================
 *	 PayPal Express Checkout Call
 * =====================================
 */
	require_once ("../library/paypalfunctions.php");

	// $res is.....
	$res = GetExpressCheckoutDetails( $_REQUEST['token'] );
	
	$finalPaymentAmount =  $res["PAYMENTREQUEST_0_AMT"];

	//Format the  parameters that were stored or received from GetExperessCheckout call.
	$token 				= $_REQUEST['token'];
	$payerID 			= $_REQUEST['PayerID'];
	$paymentType 		= 'Sale';
	$currencyCodeType 	= $res['CURRENCYCODE'];
	$items = array();
	$i = 0;
	// adding item details those set in setExpressCheckout
	while(isset($res["L_PAYMENTREQUEST_0_NAME$i"]))
	{
		$items[] = array('name' => $res["L_PAYMENTREQUEST_0_NAME$i"], 'amt' => $res["L_PAYMENTREQUEST_0_AMT$i"], 'qty' => $res["L_PAYMENTREQUEST_0_QTY$i"]);
		$i++;
	}

	$resArray = ConfirmPayment ( $token, $paymentType, $currencyCodeType, $payerID, $finalPaymentAmount, $items );
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ) {
		
		$transactionId		= $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // Unique transaction ID of the payment.
		//$transactionType 	= $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"]; // The type of transaction Possible values: l  cart l  express-checkout
		//$paymentType		= $resArray["PAYMENTINFO_0_PAYMENTTYPE"];  // Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant
		$orderTime 			= $resArray["PAYMENTINFO_0_ORDERTIME"];  // Time/date stamp of payment
		$amt				= $resArray["PAYMENTINFO_0_AMT"];  // The final amount charged, including any  taxes from your Merchant Profile.
		//$currencyCode		= $resArray["PAYMENTINFO_0_CURRENCYCODE"];  // A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD.
		//$feeAmt				= $resArray["PAYMENTINFO_0_FEEAMT"];  // PayPal fee amount charged for the transaction
		//$settleAmt			= $resArray["PAYMENTINFO_0_SETTLEAMT"];  // Amount deposited in your PayPal account after a currency conversion.
		//$taxAmt				= $resArray["PAYMENTINFO_0_TAXAMT"];  // Tax charged on the transaction.
		//$exchangeRate		= $resArray["PAYMENTINFO_0_EXCHANGERATE"];  // Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer's account.
		
		/* Take shopping cart array from database and register each student 
	     * in program. Delete shopping cart entry.
		 */ 
		
		
		
		/* Status of the payment:
		 'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
		 'Pending: The payment is pending. See the PendingReason element for more information.
		 */
		$paymentStatus = $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];

		/*  The reason the payment is pending:
		 '  none: No pending reason
		 '  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile.
		 '  echeck: The payment is pending because it was made by an eCheck that has not yet cleared.
		 '  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview.
		 '  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment.
		 '  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment.
		 '  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service.
		 */
		$pendingReason = $resArray["PAYMENTINFO_0_PENDINGREASON"];

		/* The reason for a reversal if TransactionType is reversal:
		 '  none: No reason code
		 '  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer.
		 '  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee.
		 '  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer.
		 '  refund: A reversal has occurred on this transaction because you have given the customer a refund.
		 '  other: A reversal has occurred on this transaction due to a reason not listed above.
		 */
		$reasonCode	= $resArray["PAYMENTINFO_0_REASONCODE"];
		
		?>

		<html>
			<script>
			alert("Payment Successful");
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
		<?php
	}
	else {
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

		echo "DoExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
		?>
		<html>
			<script>
			alert("Payment failed");
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
	<?php 
	}
	?>