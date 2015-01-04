<?php

class Form_Validator {

	private student_whitelist = array();
	private guardian_whitelist = array();
	
	public __construct() {
		
	}
	
	/* -----------------------------------------------------
	 * Form validation for guardians.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of student $_POST. */
	public function sanitizeStudentPost($post) {
		
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateStudentPost($post) {
		// Check for entered grade
		if (!count(test_input($_POST['grade']))) {
			$error = "Update unsuccesful. Please enter valid grade.";
		}
		if (!isset($_POST['leave_permission'])) {
			$error = "Update unsuccessful. Please indicate whether or not
    				student may leave programs on their own.";
		}
		// Check user has not checked both leave permission boxes
		elseif (count($_POST['leave_permission'])==2) {
			$error = "Update unsuccesful. Please indicate whether or not
    				student may leave programs on their own.";
		}
		// Check user has checked at least one leave permission boxes
		if (!isset($_POST['leave_permission'])) {
			$error = "Update unsuccessful. Please indicate whether or not
    				student may leave programs on their own.";
		}
		elseif (!isset($_POST['consent'])) {
			$error = "Update unsuccessful. Consent required to use this
    				registration system.";
		}
		
		// Check for non-empty first and last name
		if (!count(test_input($_POST['first_name'])) ||
				!count(test_input($_POST['last_name']))) {
					$error = "Update unsuccessful. Please enter correct first name
    					 and last name.";
				}
				
				/* Validate all user input. */
				if (!isset($_POST['guardian_group'])) {
					$guardian_group = array();
				}
				else {
					$guardian_group = $_POST['guardian_group'];
				}
	}
	
	/* -----------------------------------------------------
	 * Form validation for guardians.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of student $_POST. */
	public function sanitizeGuardianPost($post) {
		
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateGuardianPost($post) {
		//Validate user input
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error = "Please enter a valid email.";
		}
		elseif () {
			$error = "Please enter a numeric telephone number, including
				area code.";
		}
	}
	
	public function validateEmail() {
		
	}
	
	// Input tester
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	
}
   
