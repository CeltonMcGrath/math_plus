<?php

class Form_Generator {

	/* Returns html student form for students.php */
	public function studentForm($student_id, $preferred_name, $grade,
			$allergies, $medical, $photo_permission, $leave_permission, 
			$guardian_group) {

		//Display settings for new contact form
		if ($student_id == 0) {
			$new = "
	   	    	First name:
	   			<input type='text' name='first_name' />
	   			Last name:
				<input type='text' name='last_name' '/>";
			$leave_yes = '';
			$leave_no = '';	
			$consent_check = '';	
		}
		//Display settings for registered contact form
		else {
			$new = "";
			if ($leave_permission) {
				$leave_yes = 'checked';
				$leave_no = '';
			}
			else {
				$leave_yes = '';
				$leave_no = 'checked';
			}
			$consent_check = 'checked';
		}
		//Display settings for both contact types
		$text_field = $GLOBALS['text_field'];
		$photo_check = '';
		if ($photo_permission) {
			$photo_check = 'checked';
		}		
		
		return "
		<form action='students.php' method='post'>
			<input type='hidden' name='student_id' value='$student_id'/>
			".$new."
			Preferred name:
			<input type='text' name='preferred_name' value='$preferred_name'/>
			Grade:
			<input type='text' name='grade' value='$grade'/>
			<br />
			".$text_field['allergy_label']."
			<textarea name='allergies'>".$allergies."</textarea>
			".$text_field['medical_label']."
			<textarea name='medical'>".$medical."</textarea>
	   		<br />
			<input class='regular 'type='checkbox' name='photo_permission'
	   				".$photo_check."/> ".$text_field['photo_perm_label']."
   			<br /><br />
   			<input class='regular 'type='checkbox' name='leave_permission[]' 
	   				value='leave_no' ".$leave_no." />
   			".$text_field['leave_perm_no']."
   			<br />
   			<input class='regular 'type='checkbox' name='leave_permission[]' 
   					value='leave_yes' ".$leave_yes." />
   			".$text_field['leave_perm_yes']."
   			<br /><br />".$this->guardianSelectionForm($guardian_group)."
   			<br /><br />
	 		<input type='checkbox' class='regular' name='consent' 
   					".$consent_check." /> ".$text_field['student_consent']."
	 		<br /><br />
	   		<input type='submit' value='Submit Changes' />
		</form>";
	}
	
	/* Returns html guardian selection form for students.php
	 * $guardian_group is an array of 3-tuples
	 * ($guardian_id, $checked). $checked is a boolean indicating
	 * whether or not to check the checkbox.
	 */
	private function guardianSelectionForm($guardian_group) {
		
	}
	
	/* Returns html guardian form for guardians.php */
	public function guardianForm() {
		
	}
	
	/* Returns html registration form for register.php */
	public function registrationForm() {
		
	}
	
	/* Returns html login form for login.php*/
	public function loginForm() {
	
	}
}
   
