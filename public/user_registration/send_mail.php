<?php
	/*Code from http://www.9lessons.info/2013/11/php-email-verification-script.html 11/26/14*/

	function sendActivationEmail($email, $activation) {
		/* Returns true iff activation email successfully sent.*/
		$link = "http://localhost:8888/math_plus/public/user_registration/user_activation/".$activation;
		$subject = "Math+ registration activation";
		$body = "Thanks for registering with Math+. Please click <a href=".$link.">here</a> to activate your account.";
		return sendMail($email, $subject, $body);
	}
	
	function sendTemporaryPassword($email, $password) {
		/* Returns true iff temporary password email successfully sent.*/
		$subject = "Math+ registration - temporary password";
		$body = $password;
		return sendMail($email, $subject, $body);
	}
	
	function sendMail($to, $subject, $body)
	{
		require 'PHPMailer/PHPMailerAutoload.php';
		$from       = "mcgrathcelton@gmail.com";
		$mail       = new PHPMailer();
		$mail->IsSMTP(true);            // use SMTP
		$mail->IsHTML(true);
		
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
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
	    	return False;
	    }
		else
	    {
	    	return True;
	    }
	}
?>