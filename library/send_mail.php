<?php

	function sendActivationEmail($email, $activation, $reason) {
		/* Returns true iff activation email successfully sent.*/
		include 'config.php';
		$text_field = $GLOBALS['text_field'];
		$link = $siteHost."site/user_activation.php?activation=".$activation;
		if ($reason == "new user") {
			$subject = "Math+ registration activation";
			$body = "Thanks for registering with Math+. 
					Please click <a href=".$link.">here</a> to activate your account.";
		}
		elseif ($reason == "update") {
			$subject = "Math+ updated email";
			$body = "You've successfully updated your email for your Math+ account.
					Please click <a href=".$link.">here</a> to activate your new email.";
		}
		
		return sendMail($email, $subject, $body);
	}
	
	function sendTemporaryPassword($email, $password) {
		/* Returns true iff temporary password email successfully sent.*/
		$subject = "Math+ registration - temporary password";
		$body = "Your password for the Math+ registration site has been 
				reset to $password. Please login with this password and 
				change your password on the account management panel.";
		return sendMail($email, $subject, $body);
	}
	
	function sendMail($to, $subject, $body) {
		/* Returns true iff email is successfully sent.*/
		include 'config.php';
		require 'PHPMailer/PHPMailerAutoload.php';
		$from       = $emailAccount;
		$mail       = new PHPMailer();
		$mail->IsSMTP(true);            // use SMTP
		$mail->IsHTML(true);
		
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = $emailSMTPSecure; // secure transfer enabled REQUIRED for GMail
		
		$mail->Host       = $emailHost; // SMTP host
		$mail->Port       = $emailPort;                    // set the SMTP port
		$mail->Username   = $emailUsername;  // SMTP  username
		$mail->Password   = $emailPassword;  // SMTP password
		$mail->SetFrom($from, $emailSenderName);
		$mail->Subject    = $subject;
		$mail->MsgHTML($body);
		$address = $to;
		$mail->AddAddress($address, $to);
		
		return ($mail->Send());   
	}
?>
