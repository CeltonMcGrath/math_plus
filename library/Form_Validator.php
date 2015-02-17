<?php

class Form_Validator {

	private $student_whitelist = array('student_id', 'first_name', 
		'last_name', 'preferred_name', 'birthdate', 'gender', 'grade', 
		'allergies', 'medical', 'cellphone', 'perm_photo', 'perm_lunch',
			'perm_leave');
	
	private $student_non_empty = array('student_id', 'birthdate', 'grade', 'perm_photo', 'perm_lunch',
			'perm_leave');
	
	private $guardian_whitelist = array('guardian_id', 'first_name', 
			'last_name', 'email', 'phone_1', 'phone_2');
	
	private $registration_whitelist = array('email', 'password');
	
	private $account_update_whitelist = array('email', 'email2', 'oldPassword',
			 'newPassword', 'newPassword2', 'update');
	
	private $transaction_whitelist = array('response_order_id', 
			'date_stamp', 'time_stamp', 'bank_transaction_id', 'charge_total', 
			'bank_approval_code', 'response_code', 'iso_code', 'message', 
			'trans_name', 'cardholder', 'f4l4', 'card', 'expiry_date', 
			'result');
		
	/* -----------------------------------------------------
	 * Form validation for students.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of student $_POST. */
	public function sanitizeStudentPost($post) {
		$data = $this->sanitize($this->student_whitelist, $post);
		// Initialize guardian group checkbox group
		if (!isset($post['guardian_group'])) {
			$data['guardian_group'] = array();
		}
		else {
			$data['guardian_group'] = $post['guardian_group'];
		}
		return $data;
	}
	
	/* Returns -1 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateStudentPost($post) {
		// Check for non-empty values:
		foreach ($this->student_non_empty as $key) {
			if (!isset($post[$key])) {
				return "Please enter all required values.";
			}
		}
		// Check for valid birth date 
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $post['birthdate'])) {
			return "Please enter a valid birthdate.";
		}
		// Check user has checked consent box
		elseif (!isset($post['consent'])) {
			return "Consent required to use this registration system.";
		}
		else {
			return -1;
		}			
	}
	
	/* -----------------------------------------------------
	 * Form validation for guardians.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of guardian $_POST. */
	public function sanitizeGuardianPost($post) {
		$data = $this->sanitize($this->guardian_whitelist, $post);
		$data['delete'] = isset($post['delete']);
		return $data;
	}
	
	/* Returns -1 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateGuardianPost($post) {
		// Check first name
		if (isset($post['first_name']) && $this->strip_input($post['first_name'])==''){
			return "Please enter a non-empty first name.";
		}
		// Check last name
		elseif (isset($post['last_name']) && $this->strip_input($post['last_name'])==''){
			return "Please enter a non-empty last name.";
		}
		// Check primary phone
		elseif (!isset($post['phone_1']) || $this->strip_input($post['phone_1'])=='') {
			return "Please enter a non-empty phone number.";
		}
		// Check email
		elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			return "Please enter a valid email.";
		} 
		else {
			return -1;
		}	
	}
	
	/* -----------------------------------------------------
	 * Form validation for register.php
	 * -----------------------------------------------------*/
	
	/* Cleans registration form $_POST  */
	public function sanitizeRegistrationPost($post) {
		$data = $this->sanitize($this->registration_whitelist, $post);
		$data['listserv'] = isset($post['listserv']);
		return $data;
	}
	
	/* Returns -1 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateRegistrationPost($post) {
		// Check for valid e-mail address 
        if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) { 
             return "Valid email is required";
        } 
        // Check for matching e-mail address
        elseif ($post['email'] != $post['email2']) {
        	return "Email does not match.";
        }
        // Check for valid password
        elseif (!$this->validPassword($post['password'])) {
        	return "Password must contain at 
						least one number, one lowercase and one 
						uppercase letter and be at least
						at least six characters.";
        }
        // Check for matching password
        elseif($post['password'] != $post['password2']) {
        	return "Passwords do not match.";
        } 
        else {
        	return -1;
        }
	}
		

	
	/* -----------------------------------------------------
	 * Form validation for edit_account.php
	 * -----------------------------------------------------*/
	
	/* Returns -1 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateAccountUpdatePost($post) {
		// Check for valid email.
		if ($post['update']=='email') {
			if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
				return "Invalid email.";
			}
			// Check for matching emails
			elseif (!isset($post['email2']) ||
					($post['email']!=$post['email2'])) {
				return "Emails do not match.";
			}
		} elseif ($post['update']=='password') {
			//  Check for valid format of proposed password
			if (!isset($post['newPassword']) ||
					!$this->validPassword($post['newPassword'])) {
				$passwordError = "Invalid password.";
			}
			// Check for matching passwords
			elseif (!isset($post['newPassword2']) &&
					$post['newPassword'] != $post['newPassword2']) {
				$passwordError = "Passwords do not match.";
			}
		}
		return -1;
	}
	
	public function sanitizeAccountUpdatePost($post) {
		$data = $this->sanitize($this->account_update_whitelist, $post);
		$data['listserv'] = isset($post['listserv']);
		return $data;
	}
	

	/* -----------------------------------------------------
	 * Form validation for confirm.php
	 * -----------------------------------------------------*/

	/* Checks the post array  */
	public function validateTransactionPost($post) {
		if ($post['response_order_id'] > 50) {
			return "The transaction was declined. 
					If you are unsure why this happened, please 
					contact outreach@math.utoronto.edu";
		}
		else if ($post['message']!='APPROVED') {
			return "The transaction was not approved.
					If you are unsure why this happened, please 
					contact outreach@math.utoronto.edu";
		}
		else if ($post['purchase']!='purchase') {
			return "Something went wrong with the transaction.
					Please contact outreach@math.utoronto.edu";
		}
		else if ($post['result']!='1') {
			return "The transaction was declined. 
					If you are unsure why this happened, please 
					contact outreach@math.utoronto.ca";
		}
		else {
			return -1;
		}
	}
		
	public function sanitizeTransactionDetails($post) {
		$data = $this->sanitize($this->transaction_whitelist, $post);
		$data['timestamp'] = $data['date_stamp']." ".$data['time_stamp'];
		unset($data['date_stamp']);
		unset($data['time_stamp']);
		return $data;
	}
	
	/* -----------------------------------------------------
	 *  Helper functions
	 * -----------------------------------------------------*/
	
	private function strip_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	// Check validity of password.
	private function validPassword($password) {
		//return preg_match($password, '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/');
		return true;
	}
	
	/* Apply htmlspecialchars to safe keys in dangerous input */
	private function sanitize($safe_keys, $dangerous_input) {
		$data = array();
		// Salinize text inputs
		foreach ($safe_keys as $key) {
			if (isset($dangerous_input[$key])) {
				$data[$key] = htmlspecialchars($dangerous_input[$key]);
			}
		}
		return $data;
	}	

}
?>
