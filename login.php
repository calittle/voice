<?php
	session_start();
	$thispage ='';
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
	            <h3>Login</h3>
	        </div>	
			<div class="well well-lg" id="formdiv" name="formdiv">
				<form name='form' id='form' method="post">
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
			</div>  
	    </section>
	    <div class="alert alert-danger alert-dismissible" role="alert" id="errormessagediv" hidden>
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<span id="errormessage"></span>
		</div>
	    <div class="alert alert-success" role="alert" id="successdiv" hidden>
		    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
		    <span class="sr-only">Success:</span>
				You have logged in.<span id="successmessage"></span>
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
    <script src="js/purl.min.js"></script>
<script>
$(document).ready(function($) {	
	var request;
	$('form').submit(function(event){
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "login_form.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o;
				var sucksess=false;
				var msg='';
				try{
					o = JSON.parse(data);
					sucksess = o['success'];
					msg = o['msg'];
				}catch(err){
					msg = err;
				}
				if (sucksess==true){
					console.log('LOGIN OK: ' + data);
					$('#formdiv').fadeOut(500);
					$('#errormessagediv').hide();
					window.location.replace($.url().param('y'));
					//$('#successmessage').html("Click <a href='" + $.url().param('y') + "'>here</a> to continue.");
					$('#successdiv').show();
				}
				else{
					console.log('Login failure. Err=' + msg + '\n Data returned was:' + data);
					this.error(this.xhr,'System administrators have been notified.',' You can try again...');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem logging in. " + textStatus + errorThrown);
				$('#errormessagediv').show();		
				$inputs.prop("disabled",false)
			}
		});
		//request.done(function (response,textStatus,jqXHR){
		//	$('#formdiv').fadeOut(1000);
		//	$('#successdiv').show();
		//});
		request.fail(function (jqXHR, textStatus, errorThrown){
			$('#errormessage').html("There was a problem logging in: " + textStatus + errorThrown);
			$('#errormessagediv').show();		
			$inputs.prop("disabled",false)
		});
		//request.always(function(){$inputs.prop("disabled",false)});
	});
});
</script>
    </body>
</html>
