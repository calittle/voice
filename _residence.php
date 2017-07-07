
		    <div class="well well-lg" id="residence_div" name="residence_div" hidden>
				<h3>Residence</h3>				
				<form id="locationform" name="locationform" method="POST" action="locations_form.php">					
					<p class="help-block">We need your residence information for your chosen registration state.</p>
					<div class="form-group">
				    	<label for="street1Input">Address Line 1</label>
						<p class="help-block">Enter the first line of your address, e.g. street number and name.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="street1Input" name="street1Input" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="street2Input">Address Line 2</label>
						<p class="help-block">Enter the second line of your address, e.g. apartment number.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="street2Input" name="street2Input" >
						</div>
					</div>
					<div class="form-group">
				    	<label for="cityInput">City</label>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="cityInput" name="cityInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="postCodeInput">ZIP Code</label>
				    	<p class="help-block">Postal code, +4 optional</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="postCodeInput" name="postCodeInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="countyInput">County</label>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<select class="form-control required" id="countyInput" name="countyInput" required>
								<option value="">-- Select County --</option>
							</select>
						</div>
					</div>
					<div class="form-group">
				    	<label for="stateResidenceInput">State/Country</label>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="stateResidenceInput" name="stateResidenceInput" value="<?=$_SESSION['statecd']?>" readonly>
							<input type="text" class="form-control" id="countryInput" name="countryInput" value="USA" readonly>
							<input type="hidden" class="form-control" id="isResidenceInput" name="isResidenceInput" value="1" readonly>
							<input type="hidden" class="form-control" id="isMailingInput" name="isMailingInput" value="1" readonly>
						</div>
					</div>
					<hr/>
					<button type="submit" class="btn btn-default">Add Residence</button>
				</form>
				<div class="alert alert-success" role="alert" id="residence_success" hidden>
					    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
					    <span class="sr-only">Success:</span>
							Your residence data has been saved. 
				</div>
			    <div class="alert alert-danger alert-dismissible" role="alert" id="residence_error" hidden>
				    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span id="residence_error_msg"></span>
				</div>
			</div>  