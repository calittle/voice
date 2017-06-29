			<div class="well well-sm" id="logindiv" name="logindiv" hidden>
				<form name="loginform" id="loginform" method="post" action="">
					<div class="form-group">
				    	<label for="usernameInput">User Name</label>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" class="form-control" id="usernameInput" name="usernameInput" required="true">
						</div>
					</div>
				  <div class="form-group">
				    <label for="passwordInput">Password</label>
					<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
							<input type="password" class="form-control" name="passwordInput" id="passwordInput" required="true">
					</div>
				  </div>				  
				  <button type="submit" class="btn btn-default">Login</button>				  
				</form>
				<div class="alert alert-danger alert-dismissible " role="alert" id="loginerror" hidden>
				    <button type="button" class="close" data-hide="alert" XXonclick="$(this).hide()" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					<span id="loginerrormessage"></span>
				</div>
			</div>
			<div class="alert alert-success" role="alert" id="loginsuccess" hidden>
			    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
			    <span class="sr-only">Success:</span>
					You are logged in. 
			</div> 
