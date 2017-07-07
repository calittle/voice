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
