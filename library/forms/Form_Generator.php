<?php

class Form_Generator {

	/* Returns html student form for students.php */
	public function studentForm($student_id, $preferred_name, $birthdate, 
		$gender, $grade, $allergies, $medical, $perm_leave, $perm_lunch,
		$perm_photo, $cellphone, $guardian_group) {
		
		//Display settings for new contact form
		if ($student_id == 0) {
			$new = "
	   	    	*First name:
	   			<input type='text' name='first_name' 
					data-parsley-trigger='change' required/>
	   			*Last name:
				<input type='text' name='last_name' 
					data-parsley-trigger='change' required/>";
			// No gender specified
			$boy_check = '';
			$girl_check = '';
			// Default leave setting
			$leave_yes_check = '';
			$leave_no_check = 'checked';
			// Default lunch setting
			$lunch_leave_check ='';
			$lunch_stay_check = 'checked';
			$lunch_pickup_check = '';
			// Default photo permiss
			$photo_18_check = '';
			$photo_guardian_check = '';
			$photo_no_check = 'checked';
			// Consent check
			$consent_check = '';
		}
		//Display settings for registered contact form
		else {
			// Do not redisplay name fields
			$new = "";
			// Load gender checkboxes
			$boy_check = '';
			$girl_check = '';
			if ($gender=='boy') {
				$boy_check = 'checked';
			}
			elseif ($gender =='girl') {
				$girl_check = 'checked';
			}
			// Load leave permission radio buttons
			$leave_yes_check = '';
			$leave_no_check = '';
			if ($leave_permission) {
				$leave_yes_check = 'checked';
			}
			else {
				$leave_no_check = 'checked';
			}
			//Load lunch permission settings
			$lunch_leave_check ='';
			$lunch_stay_check = '';
			$lunch_pickup_check = '';
			if ($perm_lunch==0) {
				$lunch_stay_check = 'checked';
			} elseif($perm_lunch==1){
				$lunch_leave_check ='checked';
			} else {
				$lunch_pickup_check = 'checked';
			}
			//Load photo permission settings
			$photo_18_check = '';
			$photo_guardian_check = '';
			$photo_no_check = '';
			if ($perm_photo==0) {
				$photo_no_check = 'checked';
			} elseif($perm_lunch==1){
				$photo_18_check ='checked';
			} else {
				$photo_guardian_check = 'checked';
			}
			// Use must agree to use consent
			$consent_check = 'checked';
		}
		//Display settings for both contact types
		$text_field = $GLOBALS['text_field'];
		
		$form = "<form action='students.php' method='post'
				id='$student_id' data-validate='parsley' />
			<input type='hidden' name='student_id' value='$student_id'/>
			".$new."
			Preferred name:
			<input type='text' name='preferred_name' value='$preferred_name'/>
			*Birthdate (yyyy-mm-dd):
			<input type='text' name='birthdate' 
		    	pattern='^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$' 
		    	required />
			Gender:
			<input type='checkbox' name='gender' value='boy' $boy_check />
			<input type='checkbox' name='gender' value='girl' $girl_check />
			<br />
			*Grade:
			<input type='text' name='grade' value='$grade'
				data-parsley-trigger='change' required/>
			Cellphone:
			<input type='text' name='cellphone' value='$cellphone'/>
			<br />
			".$text_field['allergy_label']."
			<textarea name='allergies'>$allergies</textarea>
			".$text_field['medical_label']."
			<textarea name='medical'>$medical</textarea>
			<hr>	
			".$text_field['perm_leave']." (*)
			<br />
			<input type='radio' name='perm_leave' value='0' $leave_no_check />
			No<br />
			<input type='radio' name='perm_leave' value='1' $leave_yes_check />	
			Yes<hr>
   			".$text_field['perm_lunch']." (*)
			<input type='radio' name='perm_lunch' 
	   				value='0' $lunch_stay_check />No
			<br />
			<input type='radio' name='perm_lunch' 
	   				value='1' $lunch_leave_check />Yes
			<br />	
	   		<input type='radio' name='perm_lunch' 
	   				value='2' $lunch_pickup_check />
				Someone may pickup
   			<hr>
			".$text_field['perm_photo']." (*)
			<br />
			<input type='radio' name='perm_photo' 
	   				value='1' 
	   				$photo_18_check />
	   		This student is eighteen years of age or over and consents
			<br />
			<input type='radio' name='perm_photo' 
	   				value='2' 
	   				$photo_guardian_check />	
	   		I am the parent/guardian of the participant and I consent
			<br />
	   		<input type='radio' name='perm_photo' 
	   				value='0' 
	   				$photo_no_check />
	   		I do not consent
   			<hr>
   			".$this->guardianSelectionForm($guardian_group)."
   			<hr>
	 		<input type='checkbox' class='regular' name='consent' 
   					".$consent_check." 
   					data-parsley-trigger='change' required/> 
   			".$text_field['student_consent']."
	 		<br />
	   		<input type='submit' value='Submit Changes' />
		</form>
		<script type='text/javascript'>
  				$('#";
		$form .= $student_id;
		$form .= "' ).parsley(); </script>";
		return $form;
	}
	
	/* Returns html guardian selection form for students.php
	 * $guardian_group is an array of 2-tuples:
	 * $guardian_id => Array(0 => $guardian_name, 1 = > $checked)
	 * $checked is a boolean indicating whether or not to check the checkbox.
	 * (ie. Used to show that guardian can pick up student.)
	 */
	private function guardianSelectionForm($guardian_group) {
		$form = "Which guardian/parent contacts are allowed to pick this
				student up for lunch or at the end of daily programs?";
		
		if (empty($guardian_group)) {
			$form .= "<br /><span class='error'>
 						No guardian/parent contacts registered. Please fill
 						out the guardian and parent contact form whether or
 						not student may leave on their own.
 					</span>";
		}
		else {
			$form .= "<ul>";
			foreach ($guardian_group as $guardian_id=>$tuple) {
				$checked = '';
				if ($tuple[1]) {
					$checked = "checked";
				}
				$form .= "<li>
						<input class='regular' name='guardian_group[]'
							value='".$guardian_id."' type='checkbox'
							".$checked." />".$tuple[0]."
					</li>";
			}
			$form .= "</ul>";
		}
		return $form;
	}
	
	/* Returns html guardian form for guardians.php */
	public function guardianForm($guardian_id, $first_name, $last_name, 
			$phone_1, $phone_2, $email) {
		// Display settings for already registered guardian form
		if ($guardian_id) {
			$new = '';
			$delete = "<input type='checkbox' class='regular' name='delete'
					/> Delete";
			$submit_value = "Update contact";
		}
		// Display settings for new guardian form
		else {
			$new = "
	   	    	First name:
	   			<input type='text' name='first_name' 
					data-parsley-trigger='change' required/>
	   			Last name:
				<input type='text' name='last_name' 
					data-parsley-trigger='change' required/>";
			$delete = '';
			$submit_value = 'Submit';
		}
		// Return form	
		$form = "<form action='guardians.php' method='post'
				id='$guardian_id' data-validate='parsley' />
				<input type='hidden' name='guardian_id'
					value='$guardian_id'/>
				$new
				Primary phone: <input type='tel' name='phone_1' 
					value='$phone_1'
					data-parsley-trigger='change' required />
				<br />
				Secondary phone: <input type='tel' name='phone_2'
				value='$phone_2'/>
				<br />
				Email: <input type='email' name='email' value='$email'
					data-parsley-trigger='change' required />
				<br />
				$delete
				<br />
				<input type='submit' value='$submit_value' name='update' />
			</form>
			<script type='text/javascript'>
  				$('#";
		$form .= $guardian_id;
		$form .= "' ).parsley(); </script>";
		return $form;
	}
	
	/* Returns html registration form for register.php */
	public function registrationForm() {
		return "<form action='register.php' method='post' 
			id='registration_form' data-validate='parsley'> 
			<span class='error'>*Required fields</span>
			<br />				
		    *Email:
		    <input type='email' name='email' id='email'
		    	data-parsley-trigger='change' required /> 
		    <br />
		    *Re-enter your email:<br /> 
		    <input type='email' name='email2'
		    	data-parsley-trigger='change' required   
		    	data-parsley-equalto='#email'/> 
		    <br />
		    *Password: 
		    <input type='password' name='password' id='password'
		    	pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' 
		    	required 
		    	data-parsley-error-message='Password must contain at 
				least one number, one lowercase and one 
				uppercase letter and be at least
				at least six characters.' /> 
		    <br /> 
		    *Re-enter password:
		    <input type='password' name='password2' 
		    	data-parsley-trigger='change' required   
		    	data-parsley-equalto='#password' /> 
		    <br /><br />
		    Would you like receive email notifications about upcoming
		    programs?
		    <br />
		    <input type='checkbox' class='regular' 
		    	name='listserv' checked>
		    <br />
		    <input type='submit' value='Continue' /> 
		</form>
		<script type='text/javascript'>
  			$('#registration_form').parsley();
		</script>";
	}
	
	/* Returns html login form for login.php*/
	public function loginForm() {
	
	}
	
	/* Returns html email update form for edit_account.php */
	public function emailUpdateForm($currentEmail) {
		return "<form action='edit_account.php' method='post'
				id='email_update_form' data-validate='parsley'>
				<input type='hidden' name='update' value='email' />
			    Current email: '$currentEmail'
			    <br />
			    New email: 
			    <input type='email' name='email' id='email' 
			    data-parsley-trigger='change' required />
			    <br />
			    Re-enter new email:<br /> 
			    <input type='email' name='email2' 
			    data-parsley-trigger='change' required 
			    data-parsley-equalto='#email' />
			    <br />
			    <input type='submit' value='Update email' /> 
			</form>
			<script type='text/javascript'>
  				$('#email_update_form').parsley();
			</script>";
	}
	
	/* Returns html password update form for edit_account.php */
	public function passwordUpdateForm() {
		return "<form action='edit_account.php' method='post'
				id='pw_update_form' data-validate='parsley'>
				<input type='hidden' name='update' value='password' />
			    Current password:<br /> 
			    <input type='password' name='oldPassword' value='' 
				data-parsley-trigger='change' required />
			    <br /> 
			    New password:
		    	<input type='password' name='newPassword' id='password'
		    	pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' 
		    	required 
		    	data-parsley-error-message='Password must contain at 
				least one number, one lowercase and one 
				uppercase letter and be at least
				at least six characters.' 
		    	/> 
		   	 	<br />
		    	Re-enter new password:
		    	<input type='password' name='newPassword2' 
		    	data-parsley-trigger='change' required   
		    	data-parsley-equalto='#password' /> 
		    	<br />
			    <br /> 
			    <input type='submit' value='Update password' /> 
			</form>
			<script type='text/javascript'>
  				$('#pw_update_form').parsley();
			</script>";
	}
	
	/* Returns html listserv update form for edit_account.php */
	public function listservUpdateForm($currentSettings) {
		$checked='';
		if ($currentSettings) {
			$checked = 'checked';
		}
		return "<form action='edit_account.php' method='post'> 
				<input type='hidden' name='update' value='listserv' />
				<input type='checkbox' class='regular' 
		    		name='listserv' $checked /> 
		    	I would like receive email notifications about 
				upcoming programs.
		    	<br />
			    <input type='submit' value='Update mailing list settings' /> 
			</form>";
	}

}

?>   
