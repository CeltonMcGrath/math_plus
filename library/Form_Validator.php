<?php

class Form_Validator {

	private $student_simple_sanitize = array('student_id', 'first_name', 
		'last_name', 'preferred_name', 'grade', 'allergies', 'medical' 
	);
	
	private $guardian_simple_sanitize = array('guardian_id', 'first_name', 
			'last_name', 'email', 'phone_1', 'phone_2', 'delete');
	
	private $registration_simple_sanitize = array('email', 'password');
		
	/* -----------------------------------------------------
	 * Form validation for students.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of student $_POST. */
	public function sanitizeStudentPost($post) {
		$data = array();
		// Salinize text inputs
		foreach ($this->student_simple_sanitize as $key) {
			if (isset($post[$key])) {
				$data[$key] = htmlspecialchars($post[$key]);
			}
		}
		// Initialize checkbox group
		if (!isset($post['guardian_group'])) {
			$data['guardian_group'] = array();
		}
		else {
			$data['guardian_group'] = $post['guardian_group'];
		}
		// Photo permission
		$data['photo_permission'] = isset($post['photo_permission']);
		// Set permission to leave
		$data['leave_permission'] = ($post['leave_permission'][0]=='leave_yes');
		
		return $data;
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateStudentPost($post) {
		// Check first name
		if (isset($post['first_name']) && $this->strip_input($post['first_name'])==''){
			return "Please enter a non-empty first name.";
		}
		// Check last name
		elseif (isset($post['last_name']) && $this->strip_input($post['last_name'])==""){
			return "Please enter a non-empty last name.";
		}
		// Check for entered grade
		elseif ($this->strip_input($post['grade'])=="") {
			return "Please enter valid grade.";
		}
		// Check if permission to leave was indicated at all
		elseif (!isset($post['leave_permission'])) {
			return "Please indicate whether or not student may leave programs 
				on their own.";
		}
		// Check user has not checked both leave permission boxes
		elseif (count($post['leave_permission'])>1) {
			return "Please indicate whether or not student may leave programs 
				on their own.";
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
		$data = array();
		// Salinize text inputs
		foreach ($this->guardian_simple_sanitize as $key) {
			if (isset($post[$key])) {
				$data[$key] = htmlspecialchars($post[$key]);
			}
		}
		return $data;
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
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
	
	/* Sanitizes each value of guardian $_POST. */
	public function sanitizeRegistrationPost($post) {
		$data = array();
		// Salinize text inputs
		foreach ($this->registration_simple_sanitize as $key) {
			if (isset($post[$key])) {
				$data[$key] = htmlspecialchars($post[$key]);
			}
		}
		$data['listserv'] = isset($post['listserv']);
		return $data;
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateRegistrationPost($post) {
		/*$emailEntry = $_POST['email'];
    	
        // Check for valid e-mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { 
            $emailErr = "Valid email is required";
        } 
        // Check for matching e-mail address
        elseif ($_POST['email'] != $_POST['email2']) {
        	$email2Err = "Email does not match.";
        }
        // Check for non-empty password
        elseif(empty($_POST['password']))  { 
            $passwordErr = "Password is required."; 
        } 
        // Check for valid password
        elseif (!validPassword($_POST['password'])) {
        	$passwordErr = "Pass must contain at least 8 characters, contain 
        			only letters, numbers or ...";
        }
        // Check for matching password
        elseif($_POST['password'] != $_POST['password2']) {
        	$password2Err = "Passwords do not match.";
        }
        // Check for uniqueness of email 
        elseif (userExists($_POST['email'], $db)) {
        	$errorPhrase = "This email is already in use.";
        }*/
		return 0;
	}
	
	
	
	
	// Input tester
	function strip_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	
}
   
