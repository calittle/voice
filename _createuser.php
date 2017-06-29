		    <div class="well well-lg" id="create_user_div" name="create_user_div">
	            <h3>Create a User Account</h3>				
				<form name="createuserform" id="createuserform" method="post" action="">
					<div class="form-group">
				    	<label for="usernameInput">User Name</label>
						<p class="help-block">Pick a username that's unique. You can use your email address if you like.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" class="form-control" id="usernameInput" name="usernameInput" required="true">
						</div>
					</div>
					<div class="form-group">
						<label for="emailInput">Email address</label>
						<p class="help-block">Enter your email address. This will be used to recover your password only.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
							<input type="email" class="form-control" name="emailInput" id="emailInput" required="true">				    
						</div>
					</div>
				  <div class="form-group">
				    <label for="passwordInput">Password</label>
				    <p class="help-block">Choose a sufficiently strong password, e.g. 8+ characters, mixed case, with alphanumerics and special characters.</p>
					<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
							<input type="password" class="form-control" name="passwordInput" id="passwordInput" required="true">
					</div>
					<span class="label label-default" id="passstrength" name="passstrength"></span>
				  </div>
				  
				  <button type="submit" class="btn btn-default">Create User Account</button>
				  
				</form>
				<p>Already a user? <a href="#" onclick="showlogin();">Login</a> instead.</p>
				<div class="alert alert-danger alert-dismissible " role="alert" id="create_user_error" hidden>
				    <button type="button" class="close" data-hide="alert" XXonclick="$(this).hide()" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span id="create_user_error_msg"></span>
				</div>
			</div>
			<div class="alert alert-success" role="alert" id="create_user_success" hidden>
			    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
			    <span class="sr-only">Success:</span>
					Your user account has been created. 
			</div> 
