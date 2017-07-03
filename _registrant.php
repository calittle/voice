		    <div class="well well-lg" id="registrant_div" name="registrant_div" hidden>
	            <h3>Add Registrant Information</h3>				
				<form id="registrant_form" name="registrant_form"  method="post" action="">
					<div class="form-group">
				    	<label for="firstNameInput">First Name</label>
						<p class="help-block">Enter your first name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="firstNameInput" name="firstNameInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="middleNameInput">Middle Name</label>
						<p class="help-block">Enter your middle name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-question-sign"></span></span>
							<input type="text" class="form-control" id="middleNameInput" name="middleNameInput">
						</div>
					</div>
					<div class="form-group">
				    	<label for="lastNameInput">Last Name</label>
						<p class="help-block">Enter your Last name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="lastNameInput" name="lastNameInput" required>					
						</div>
					</div>
					<div class="form-group">
				    	<label for="suffixNameInput">Suffix</label>
						<p class="help-block">Enter your name suffix (e.g. Jr, II, etc) as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-question-sign"></span></span>							
							<input type="text" class="form-control" id="suffixNameInput" name="suffixNameInput">
						</div>
					</div>
					<hr class="separator">
					<div class="form-group">
				    	<label for="phoneInput">Phone Number</label>
						<p class="help-block">Just in case we need to contact you about your registration request.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>							
							<input type="text" class="form-control" id="phoneInput" name="phoneInput">
						</div>
					</div>
					<div class="form-group">
				    	<label for="birthDateInput">Birth date</label>
						<p class="help-block">Enter your birthdate as YYYY-MM-dd, or use the picker.</p>
						<div class="input-group date">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>							
							<input type="text" class="form-control" id="birthDateInput" name="birthDateInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="stateIdInput">State ID Number</label>
						<p class="help-block">Enter state identification number (ex. Driver License number) </p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>							
							<input type="text" class="form-control" id="stateIdInput" name="stateIdInput" >
						</div>
					</div>
					<!-- uncomment if need to support another ID number (e.g. SSN, or some other scheme).-->
					<div class="form-group">
				    	<label for="fedIdInput">Last 4 digits of SSN</label>
						<p class="help-block">Please enter last four digits of your Social Security Number.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>							
							<input type="text" class="form-control" id="fedIdInput" name="fedIdInput" required>
						</div>
					</div>
					
					<!-- Note: in a single-state implementation you could simply hard-code this value and not use this input -->
					<div class="form-group">
				    	<label for="stateInput">Registration State</label>
						<p class="help-block">Choose the state in which you are registering</p>
						<div class="input-group">
							<select class="form-control required" id="stateInput" name="stateInput" required>
								<option value="">-- Select State --</option>
<?php
	foreach ($states as $row){
		print '<option value="'.$row['State Code'].'">'.$row['State'].' ('.$row['State Code'].')</option>';
	}
	?>

							</select>
						</div>
					</div>
					<hr class="separator">					
					<div class="form-group">
				    	<label for="ethnicityInput">Ethnicity</label>
						<p class="help-block">This question is asked to comply with federal regulations and is optional.</p>
						<div class="input-group">
							<select class="form-control" id="ethnicityInput" name="ethnicityInput">
								<option value="UN" selected>-- Select Ethnicity --</option>
<?php
	foreach ($eths as $row){
		print '<option value="'.$row['ETHNICITYCD'].'">'.$row['ETHNICITY'].'</option>';
	}
	?>
							</select>
						</div>
					</div>
					<div class="form-group">
				    	<label for="genderInput">Gender</label>
						<p class="help-block">This question is asked to comply with federal regulations and is optional.</p>
						<div class="input-group">
							<select class="form-control" id="genderInput" name="genderInput">
								<option value="UNS" selected>-- Select Gender --</option>
<?php
	foreach ($genders as $row){
		print '<option value="'.$row['GENDERCD'].'">'.$row['GENDER'].'</option>';
	}
	?>
							</select>
						</div>
					</div>					
				  <button type="submit" class="btn btn-default">Next</button>
				</form>
				
				<div class="alert alert-danger alert-dismissible " role="alert" id="registrant_error" hidden>
				    <button type="button" class="close" data-hide="alert" XXonclick="$(this).hide()" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span id="registrant_error_msg"></span>
				</div>
			</div>


		    <div class="alert alert-success" role="alert" id="registrant_success" hidden>
				    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
				    <span class="sr-only">Success:</span>
						Your registrant data has been saved. 
			</div>
		    <div class="well well-lg" id="registrant2_div" name="registrant2_div" hidden>
				<h3>Affirmations</h3>				
				
				<form name="affirmForm" id="affirmForm"  method="post" action="">					
					<p class="help-block">Registrants must meet qualifications to register to vote. Your selected registration state specifies that registrants must:</p>
					<ul id="affirmations">
					</ul>
					<div class="form-group form-check has-danger">
				    	<label for="affirmInput" class="form-check-label">
							<input type="checkbox" class="form-check-input" id="affirmInput" name="affirmInput" value="1">		
							By ticking this checkbox, you swear or affirm you meet the above qualifications.
													
						</label>
					</div>
<hr/>
					<div class="form-group">
				    	<label for="partyInput">Party Affiliation</label>
						<p class="help-block">Optionally select a party affiliation.</p>
						<div class="input-group">
							<select class="form-control required" id="partyInput" name="partyInput" required="false">
								<option value="UNS" selected>-- Select Party --</option>
							</select>
						</div>
					</div>
				  
				  <button type="submit" class="btn btn-default">Swear/Affirm</button>
				  
				</form>
				
				<div class="alert alert-danger alert-dismissible " role="alert" id="registrant2_error" hidden>
				    <button type="button" class="close" data-hide="alert" XXonclick="$(this).hide()" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span id="registrant2_error_msg"></span>
				</div>
		    </div>
