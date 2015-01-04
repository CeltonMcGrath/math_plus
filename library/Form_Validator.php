<?php

class Form_Validator {

	private $student_simple_sanitize = array('student_id', 'first_name', 
		'last_name', 'preferred_name', 'grade', 'allergies', 'medical' 
	);
	private $guardian_simple_sanitize = array('guardian_id', 'first_name', 
			'last_name', 'email', 'phone_1', 'phone_2');
		
	/* -----------------------------------------------------
	 * Form validation for students.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of student $_POST. */
	public function sanitizeStudentPost($post) {
		$data = array();
		// Salinize text inputs
		foreach ($student_simple_sanitize as $key) {
			$data[$key] = htmlspecialchars($post[$key]);
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
		if (isset($post['first_name']) && strip_input($post['first_name'])==""){
			return "Please enter a non-empty first name.";;
		}
		// Check last name
		if (isset($post['last_name']) && strip_input($post['last_name'])==""){
			return "Please enter a non-empty last name."
		}
		// Check for entered grade
		if (strip_input($post['grade']=="")) {
			return "Please enter valid grade.";
		}
		// Check if permission to leave was indicated at all
		if (!isset($post['leave_permission'])) {
			return "Please indicate whether or not student may leave programs 
				on their own.";
		}
		// Check user has not checked both leave permission boxes
		elseif (count($post['leave_permission'])>1) {
			return "Please indicate whether or not student may leave programs 
				on their own.";
		}
		// Check user has checked consent box
		elseif (!isset($_POST['consent'])) {
			return "Consent required to use this registration system.";
		}
		else {
			return 0;
		}			
	}
	
	/* -----------------------------------------------------
	 * Form validation for guardians.php
	 * -----------------------------------------------------*/
	
	/* Sanitizes each value of guardian $_POST. */
	public function sanitizeGuardianPost($post) {
		$data = array();
		// Salinize text inputs
		foreach ($guardian_simple_sanitize as $key) {
			$data[$key] = htmlspecialchars($post[$key]);
		}
		return $data;
	}
	
	/* Returns 0 if each  value in POST array is valid, error code
	 * if not.*/
	public function validateGuardianPost($post) {
		// Check first name
		if (isset($post['first_name']) && strip_input($post['first_name'])!=""){
			return "Please enter a non-empty first name."
		}
		// Check last name
		if (isset($post['last_name']) && strip_input($post['last_name'])!=""){
			return "Please enter a non-empty last name."
		}
		// Check email
		if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			return "Please enter a valid email.";
		} 
		// Check phone 1
		elseif () {
			return "Incorrect primary phone number. Please enter a numeric 
					telephone number, including area code.";
		}
		// Check phone 2
		else if () {
			return "Incorrect secondary phone number. Please enter a numeric 
					telephone number, including area code.";
		}
		else {
			return 0;
		}	
	}
	
	// Input tester
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	
}
   
