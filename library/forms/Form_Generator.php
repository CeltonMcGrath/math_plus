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
	   			<input type='text' name='first_name' 
					data-parsley-trigger='change' required/>
	   			Last name:
				<input type='text' name='last_name' 
					data-parsley-trigger='change' required/>";
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
		
		$form = "<form action='students.php' method='post'
				id='$guardian_id' data-validate='parsley' />
			<input type='hidden' name='student_id' value='$student_id'/>
			".$new."
			Preferred name:
			<input type='text' name='preferred_name' value='$preferred_name'/>
			Grade:
			<input type='text' name='grade' value='$grade'
				data-parsley-trigger='change' required/>
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
			$delete = "
				Delete:
				<input type='radio' name='delete'
				value='yes'/> Yes
				<input type='radio' name='delete' value='no' checked/> No";
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
		    <input type='submit' value='Register' /> 
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
			    Current email: '$currentEmail'>
			    <br />
			    New email: 
			    <input type='email' name='email' 
			    data-parsley-trigger='change' required />
			    <br />
			    Re-enter new email:<br /> 
			    <input type='email' name='email2' 
			    data-parsley-trigger='change' required />
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
		    	<input type='password' name='password' id='password'
		    	pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' 
		    	required 
		    	data-parsley-error-message='Password must contain at 
				least one number, one lowercase and one 
				uppercase letter and be at least
				at least six characters.' 
		    	/> 
		   	 	<br />
		    	Re-enter new password:
		    	<input type='password' name='password2' 
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
		if ($currentSettings) {
			$checked = 'checked';
		}
		return '<form action='edit_account.php' method='post'> 
				<input type='hidden' name='update' value='listserv' />
				<input type='checkbox' class='regular' 
		    		name='listserv' '$checked' /> 
		    	I would like receive email notifications about 
				upcoming programs.
		    	<br />
			    <input type='submit' value='Update mailing list settings' /> 
			</form>';
	}

}
   
