<?php
	/*Code from http://www.9lessons.info/2013/11/php-email-verification-script.html 11/26/14*/
	function Send_Mail($to, $subject, $body)
	{
		require 'class.phpmailer.php';
		$from       = "mcgrathcelton@gmail.com";
		$mail       = new PHPMailer();
		$mail->IsSMTP(true);            // use SMTP
		$mail->IsHTML(true);
		
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		
		$mail->Host       = "smtp.gmail.com"; // SMTP host
		$mail->Port       =  465;                    // set the SMTP port
		$mail->Username   = "mcgrathcelton@gmail.com";  // SMTP  username
		$mail->Password   = "partition67";  // SMTP password
		$mail->SetFrom($from, 'Celton McGrath');
		$mail->Subject    = $subject;
		$mail->MsgHTML($body);
		$address = $to;
		$mail->AddAddress($address, $to);
		if(!$mail->Send())
	    {
	    	echo "Mailer Error: " . $mail->ErrorInfo;
	    }
		else
	    {
	    	echo "Message has been sent";
	    }
	}
?>