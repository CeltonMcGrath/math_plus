<?php

class Form_Generator {

	/* Returns html student form for students.php */
	public function studentForm($student_id, $preferred_name, $birthdate, 
		$gender, $grade, $allergies, $medical, $perm_leave, $perm_lunch,
		$perm_photo, $cellphone, $guardian_group) {
		
		//Display settings for new contact form
		if ($student_id == 0) {
			$new = "
			<!-- First name -->
			<div class='form-group'>
				<label class='col-md-4 control-label' for='first_name'>First name</label>
				<div class='col-md-4'>
					<input id='first_name' name='first_name' type='text' class='form-control input-md' required 
						data-parsley-trigger='change'>
				</div>
			</div>
			<!-- Last name -->
			<div class='form-group'>
			<label class='col-md-4 control-label' for='last_name'>Last name</label>
				<div class='col-md-4'>
					<input id='last_name' name='last_name' type='text' class='form-control input-md' required 
						data-parsley-trigger='change'>
				</div>
			</div>";
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
			if ($perm_leave) {
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
		
		return "<form class='form-horizontal' action='students.php' method='post'
				id='form-$student_id'>
		<input type='hidden' name='student_id' value='$student_id'/>
		<fieldset>		
		$new
		<!-- Preferred name -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-preferred_name'>Preferred name</label>
			<div class='col-md-4'>
				<input id='$student_id-preferred_name' name='preferred_name' type='text' class='form-control input-md' value='$preferred_name' >		
			</div>
		</div>		
		<!-- Birthday -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-birth_\date'>Birthdate</label>
			<div class='col-md-4'>
				<input id='$student_id-birthdate' name='birthdate' type='text' placeholder='' class='form-control input-md' required value='$birthdate' 
					data-parsley-trigger='change' pattern='^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$' >
				<span class='help-block'>yyyy-mm-dd</span>
			</div>
		</div>				
		<!-- Gender -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='gender'>Gender</label>
			<div class='col-md-4'>
				<div class='radio'>
					<label for='gender-0'>
						<input type='radio' name='gender' id='$student_id-gender-0' value='boy' $boy_check >
						Male
					</label>
				</div>
				<div class='radio'>
					<label for='gender-1'>
						<input type='radio' name='gender' id='$student_id-gender-1' value='girl' $girl_check >
						Female
					</label>
				</div>
			</div>
		</div>		
		<!-- Grade -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-grade'>Grade</label>
			<div class='col-md-4'>
				<input id='$student_id-grade' name='grade' type='text' class='form-control input-md' required 
					data-parsley-trigger='change' value='$grade' >	
			</div>
		</div>
		<!-- Cellphone -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-cellphone'>Cellphone</label>
			<div class='col-md-4'>
				<input id='$student_id-cellphone' name='cellphone' type='text' class='form-control input-md' value='$cellphone' >				
			</div>
		</div>		
		<!-- Allergies -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-allergies'>
				Please let us know of any allergies this student may have. 
				If the student carries an epipen, please say so.
			</label>
			<div class='col-md-4'>
				<textarea class='form-control' id='$student_id-allergies' name='allergies'>$allergies</textarea>
			</div>
		</div>		
		<!-- Medical -->
		<div class='form-group'>
			<label class='col-md-4 control-label' for='$student_id-medical'>Medical information</label>
			<div class='col-md-4'>
				<textarea class='form-control' id='$student_id-medical' name='medical'>$medical</textarea>
			</div>
		</div>				
		<!-- Leave permission -->
		<div class='form-group'>
			<label class='col-md-8 control-label' for='$student_id-perm_leave'>".$text_field['perm_leave']."</label>
			<div class='col-md-4'>
				<div class='radio'>
					<label for='$student_id-perm_leave-0'>
						<input type='radio' name='perm_leave' id='$student_id-perm_leave-0' value='0' $leave_no_check >
						No
					</label>
				</div>
				<div class='radio'>
					<label for='$student_id-perm_leave-1'>
						<input type='radio' name='perm_leave' id='$student_id-perm_leave-1' value='1' $leave_yes_check >
						Yes
					</label>
				</div>
			</div>
		</div>		
		<!-- Lunch leave permission -->
		<div class='form-group'>
			<label class='col-md-8 control-label' for='$student_id-perm_lunch'>".$text_field['perm_lunch']."</label>
			<div class='col-md-4'>
				<div class='radio'>
					<label for='$student_id-perm_lunch-0'>
						<input type='radio' name='perm_lunch' id='$student_id-perm_lunch-0' value='0' $lunch_stay_check >
						No
					</label>
				</div>
				<div class='radio'>
					<label for='$student_id-perm_lunch-1'>
						<input type='radio' name='perm_lunch' id='$student_id-perm_lunch-1' value='1' $lunch_leave_check >
						Yes
					</label>
				</div>
				<div class='radio'>
					<label for='$student_id-perm_lunch-2'>
						<input type='radio' name='perm_lunch' id='$student_id-perm_lunch-2' value='2' $lunch_pickup_check >
						Someone may pick up
					</label>
				</div>
			</div>
		</div>
		<!-- Photo permission -->
		<div class='form-group'>
			<label class='col-md-8 control-label' for='$student_id-perm_photo'>".$text_field['perm_photo']."</label>
			<div class='col-md-4'>
				<div class='radio'>
					<label for='$student_id-perm_photo-0'>
						<input type='radio' name='perm_photo' id='$student_id-perm_photo-0' value='1' $photo_18_check >
						This student is eighteen years of age or over and consents
					</label>
				</div>
				<div class='radio'>
					<label for='$student_id-perm_photo-1'>
						<input type='radio' name='perm_photo' id='$student_id-perm_photo-1' value='2' $photo_guardian_check >
						I am the parent/guardian of the participant and I consent
					</label>
				</div>
				<div class='radio'>
					<label for='$student_id-perm_photo-2'>
						<input type='radio' name='perm_photo' id='$student_id-perm_photo-2' value='0' $photo_no_check>
						I do not consent
					</label>
				</div>
			</div>
		</div>
		<!-- Guardian selection form -->
		".$this->guardianSelectionForm($guardian_group)."
		<!-- Consent check & Button -->
		<div class='form-group'>
			<div class='col-md-8'>
				<div class='checkbox'>
					<label for='$student_id-consent-0'>
						<input type='checkbox' name='consent' id='$student_id-consent-0' value='1' checked >
						".$text_field['student_consent']."
					</label>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
		</div>	
		<div class='col-md-4'>
		    <button type='submit' id='$student_id-submit' name='update' class='btn btn-lg btn-primary btn-block' value='Submit'>Submit</button>
		</div>		
		</fieldset>
		</form>
			<script type='text/javascript'>
	  			$('#form-".$student_id."').parsley();
			</script>";				
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
			$delete =  "<button type='submit' id='$guardian_id-submit' name='submit' class='btn btn-danger' value='Delete'>Delete</button>";
			$submit_value = "Update contact";
		}
		// Display settings for new guardian form
		else {
			$new = "
				<div class='form-group'>
  					<label class='col-md-4 control-label' 
						for='$guardian_id-first_name'>First name</label>  
 					 <div class='col-md-4'>
  					<input id='$guardian_id-first_name' name='first_name' 
						type='text' class='form-control 
						input-md' required>
 					 </div>
				</div>
				<div class='form-group'>
  					<label class='col-md-4 control-label' 
						for='$guardian_id-last_name'>Last name</label>  
  					<div class='col-md-4'>
  					<input id='$guardian_id-last_name' name='last_name' type='text' 
						class='form-control input-md' required>   
  				</div>
			</div>";
			$delete = '';
			$submit_value = 'Add new contact';
		}
		// Return form	
		$form = "<form id='form-$guardian_id' action='guardians.php' method='post'
					class='form-horizontal' />
				<fieldset>
				<input type='hidden' name='guardian_id'
					value='$guardian_id'/>
				<!-- Name -->
				$new
				<!-- Primary phone-->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='$guardian_id-phone_1'>Primary phone</label>  
 					 <div class='col-md-4'>
  					<input id='$guardian_id-phone_1' name='phone_1' type='tel' value='$phone_1' class='form-control input-md' required
  							data-parsley-trigger='change'>    
 					 </div>
				</div>
				<!-- Secondary phone-->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='$guardian_id-phone_2'>Secondary phone</label>  
  					<div class='col-md-4'>
  					<input id='$guardian_id-phone_2' name='phone_2' type='tel' value='$phone_2' class='form-control input-md'>   
  					</div>
				</div>
				<!-- Email -->
				<div class='form-group'>
				  <label class='col-md-4 control-label' for='$guardian_id-email'>Email</label>  
				  <div class='col-md-4'>
				  <input id='$guardian_id-email' name='email' type='email' value='$email' class='form-control input-md' required
							data-parsley-trigger='change'>				    
				  </div>
				</div>
				<!-- Button -->
				<div class='form-group'>
				  <label class='col-md-4 control-label' for='submit'></label>
				  <div class='col-md-8'>
				    <button type='submit' id='$guardian_id-submit' name='update' class='btn btn-success' value='$submit_value'>$submit_value</button>
				    $delete 
				  </div>
				</div>
				</fieldset>
			</form>
			<script type='text/javascript'>
	  			$('#form-".$guardian_id."').parsley();
			</script>";	
		return $form;
	}
	
	/* Returns html registration form for register.php */
	public function registrationForm($error, $success) {
		return "<form action='register.php' method='post' class='form-signin' 
				id='register-form'>
			<h2 class='form-signin-heading'>Create an account</h2>
			<div class='control-group'>
				<div class='controls'>
					<input class='form-control' type='email' 
							name='email' id='email'
							required 
							data-parsley-trigger='change' 
							placeholder='Email address' />
				</div>
				<div class='controls'>
					<input class='form-control' type='email' 
							name='email2'
							required
							data-parsley-trigger='change' 
							placeholder='Re-enter email address' 
							data-equalto='#email' />
				</div>
			</div>
			<div class='control-group'>
				<div class='controls'>
					<input class='form-control' type='password' 
							name='password' id='password' 
							required
							data-parsley-trigger='change'  
							pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}'
							placeholder='Password' />
				</div>
				<div class='controls'>
					<p>Password must contain at 
					least one number, one lowercase and one 
					uppercase letter and be at least
					at least six characters.</p>
				</div>
				<div class='controls'>
					<input class='form-control' type='password' 
							name='password2'
							required
							data-parsley-trigger='change' 
							placeholder='Re-enter password'
							data-equalto='#password' />
				</div>
				<div class='controls'>
					<input type='checkbox' name='listserv' id='listserv' />
					I would like to receive email notifications about upcoming
		   				programs.
				</div>
			</div>
			<br />
			<button class='btn btn-lg btn-primary btn-block' 
		        	type='submit'>Continue</button>
			<br />
			<a href='login.php'>Return to login</a>
		</form>
		<script type='text/javascript'>
  			$('#register-form').parsley();
		</script>";		
	}
	
	
	/* Returns html email update form for edit_account.php */
	public function emailUpdateForm($currentEmail) {
		return "<form id='email_update_form' action='edit_account.php' method='post'
				 class='form-horizontal' />
				<fieldset>
				<input type='hidden' name='update' value='email' />	
				<!-- Old email -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='email'>Current email</label>  
 					 <div class='col-md-4'>
  						<input class='form-control input-md' disabled value='$currentEmail'>    
 					 </div>
				</div>			
				<!-- Enter email -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='email'>New email</label>  
 					 <div class='col-md-4'>
  					<input id='email' name='email' type='email' class='form-control input-md' required
  							data-parsley-trigger='change'>    
 					 </div>
				</div>
				<!-- Enter email again -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='email'>Enter new email again</label>  
 					 <div class='col-md-4'>
  					<input id='email2' name='email2' type='email' class='form-control input-md' required
  							data-parsley-trigger='change' data-equalto='#email' >    
 					 </div>
				</div>
				<div class='col-md-2'>
				    <button type='submit' id='submit' name='update' class='btn btn-primary' value='Submit'>Submit</button>
				</div>		
				</fieldset>
				</form>
				<script type='text/javascript'>
	  				$('#email_update_form').parsley();
				</script>";
	}
	
	/* Returns html password update form for edit_account.php */
	public function passwordUpdateForm() { 
		return "<form id='pw_update_form' action='edit_account.php' method='post'
				 class='form-horizontal' />
				<fieldset>
				<input type='hidden' name='update' value='password' />	
				<!-- Current password -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='oldPassword'>Current password</label>  
 					 <div class='col-md-4'>
  						<input id='oldPassword' name='oldPassword' class='form-control input-md' type='password' data-parsley-trigger='change' required />    
 					 </div>
				</div>			
				<!-- Enter new password -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='newPassword'>New password</label>  
 					 <div class='col-md-4'>
  						<input id='newPassword' name='newPassword' type='password' class='form-control input-md' required
  							data-parsley-trigger='change' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' >    
 					 </div>
					<div class='col-md-4'>
						<p>Password must contain at 
							least one number, one lowercase and one 
							uppercase letter and be at least
							at least six characters.</p>
					</div>
				</div>
				<!-- Enter new password -->
				<div class='form-group'>
  					<label class='col-md-4 control-label' for='newPassword2'>New password again</label>  
 					 <div class='col-md-4'>
  						<input id='newPassword2' name='newPassword2' type='password' class='form-control input-md' required
  							data-parsley-trigger='change' data-equalto='#newPassword' >    
 					 </div>
				</div>
				<div class='col-md-2'>
				    <button type='submit' id='submit' name='update' class='btn btn-primary' value='Submit'>Submit</button>
				</div>		
				</fieldset>
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
		return "<form action='edit_account.php' method='post'
				 class='form-horizontal' />
				<fieldset>
				<input type='hidden' name='update' value='listserv' />	
				<div class='form-group'>
					<div class='col-md-8'>
						<div class='checkbox'>
							<label for='listserv'>
								<input type='checkbox' name='listserv' id='listserv' value='1' $checked >
								I would like to receive email notifications about upcoming programs.
							</label>
						</div>
					</div>
				</div>							
				<div class='col-md-2'>
				    <button type='submit' id='submit' name='update' class='btn btn-primary' value='Submit'>Submit</button>
				</div>		
				</fieldset>
				</form>";
	}

}
?>