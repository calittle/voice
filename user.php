<?php
	session_start();
	$thispage = 'register';
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>VOICE &#9745;</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel=stylesheet href="css/mystyles.css"/>    
    <link href="https://fonts.googleapis.com/css?family=Lora|Raleway|Source+Code+Pro" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <nav class="navbar navbar-inverse">
	        <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/voice">VOICE</a>
                </div>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <?php include 'menu_left.php'?>                    
                    <?php include 'menu_right.php'?>
                </div>
	        </div>
        </nav>
        <h1 class="sr-only">Home</h1>
    </header>
	<div class="container">
	    <section>
		    <div class="alert alert-info alert-dismissible" role="alert" id="headerdiv">
	            <h3>Create a User Account</h3>
				<p>You need a <strong>user account</strong> to use VOICE. If you already have a user account, you can skip this step and proceed to <a href="login.php?y=registrant.php">register</a>.</p>
	        </div>	

			<div class="well well-lg" id="formdiv" name="formdiv">
				<form name='form' id='form' method="post">
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
					<span class="label label-default" id="passstrength"></span>
				  </div>
				  <button type="submit" class="btn btn-default">Create User Account</button>
				</form>
			</div>  
	    </section>
	    <div class="alert alert-danger alert-dismissible" role="alert" id="errormessagediv" hidden>
		    <button type="button" class="close" onclick="$('#errormessagediv').hide()" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<span id="errormessage"></span>
		</div>
	    <div class="alert alert-success" role="alert" id="successdiv" hidden>
		    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
		    <span class="sr-only">Success:</span>
				Your user account has been created. Please proceed to the <a href="registrant.php">next step</a>. <A href="registrant.php"><button type="button" class="btn btn-default">Next</button></A>
		</div>
	    <div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
			</div>
		</div>
    </div>
    <nav id="pagefooter" class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <div class="container">
            <div class="col-xs-12 text-center navbar-text">
				<p class="text-muted">Copyright &copy; 2017 <a href="mailto:little_charles1@columbusstate.edu">Charles Little</a>, All rights reserved.</p>
            </div>
        </div>
    </nav>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function($) {
	$('#passwordInput').keyup(function(e) {
     var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
     var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
     var enoughRegex = new RegExp("(?=.{6,}).*", "g");
     if (false == enoughRegex.test($(this).val())) {
             $('#passstrength').html('Please enter more characters.');
     } else if (strongRegex.test($(this).val())) {
             $('#passstrength').removeClass().addClass('label label-success');
             $('#passstrength').html('Well done!');
     } else if (mediumRegex.test($(this).val())) {
             $('#passstrength').removeClass().addClass('label label-warning');
             $('#passstrength').html('Ok, but can you do more?');
     } else {
             $('#passstrength').removeClass().addClass('label label-danger');
             $('#passstrength').html('This is weak; add more characters!');
     }
     return true;
	});
	
	$('form').validate({
	    rules: {
	        usernameInput: {
	            minlength: 3,
	            maxlength: 256,
	            required: true
	        },
	        emailInput: {
	            minlength: 5,
	            maxlength: 256,
	            required: true
	        },
	        passwordInput: {
	            minlength: 8,
	            maxlength: 64,
	            required: true
	        }
	    },
	    highlight: function(element) {
	        $(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.parent('.input-group').length) {
	            error.insertAfter(element.parent());
	        } else {
	            error.insertAfter(element);
	        }
	    }
	});
	var request;
	$('form').submit(function(event){
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "user_form.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o = JSON.parse(data);
				var sucksess = o['success'];
				if (sucksess==true){
					console.log('Created user account: ' + data);
					$('#headerdiv').fadeOut(500);
					$('#formdiv').fadeOut(500);
					$('#successdiv').show();
				}
				else{
					console.log('Unable to create user account:' + data);
					this.error(this.xhr,o['message'],' Try again!');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem creating your user account: " + textStatus + errorThrown);
				$('#errormessagediv').show();		
				$inputs.prop("disabled",false)
			}
		});
		//request.done(function (response,textStatus,jqXHR){
		//	$('#formdiv').fadeOut(1000);
		//	$('#successdiv').show();
		//});
		request.fail(function (jqXHR, textStatus, errorThrown){
			$('#errormessage').html("There was a problem creating your user account: " + textStatus + errorThrown);
			$('#errormessagediv').show();		
			$inputs.prop("disabled",false)
		});
		//request.always(function(){$inputs.prop("disabled",false)});
	});
});
</script>
    </body>
</html>
