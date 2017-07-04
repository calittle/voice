<?php
	session_start();
	$thispage = 'index';
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
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
<link rel="icon" type="image/png" sizes="194x194" href="favicon-194x194.png">
<link rel="icon" type="image/png" sizes="192x192" href="android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
<link rel="manifest" href="manifest.json">
<link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
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
                    <?php
	                    include 'menu_left.php';                    
						include 'menu_right.php';
						?>       
                </div>
	        </div>
        </nav>
        <h1 class="sr-only">Home</h1>
    </header>
	<div class="container">
	    <section>
			<div class="jumbotron">
	            <h1>Welcome to VOICE.</h1>
				<p>Hi! New here? VOICE is where you can register to vote and participate in online voting. VOICE: making it easy to exercise your enfranchisement, and your civic duty.</p>
		</div>
		<div class="alert alert-info alert-dismissible" role="alert" id="logoutdiv" hidden>
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Message:</span>You have been logged out.
		</div>	
		<div class="alert alert-info alert-dismissible" role="alert" id="noaccess" hidden>
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span>You do not have access to that area.</span>
		</div>	
		<div class="row">
		  <div class="col-md-4"><div class="well"><a href="vote.php"><h1 class="text-center"><span class="glyphicon glyphicon-ok"></span> Vote</h1></a></div></div>
		  <div class="col-md-4"><div class="well"><a href="register.php"><h1 class="text-center"><span class="glyphicon glyphicon-user"></span> Register</h1></a></div></div>
		  <div class="col-md-4"><div class="well"><a href="account.php"><h1 class="text-center"><span class="glyphicon glyphicon-wrench"></span> My Account</h1></a></div></div>
		</div>       
<div class="well well-sm">
	<p class="text-center text-muted">What is VOICE? It is <strong>V</strong>ote-<strong>O</strong>nline <strong>I</strong>nteractive <strong>C</strong>ivic <strong>E</strong>nablement. It's <em>your</em> voice being heard!</p>
</div>
	    </section>
    </div>
    <nav id="pagefooter" class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <div class="container">
            <div class="col-xs-12 text-center navbar-text">
				<p class="text-muted">Copyright &copy; 2017 <a href="mailto:little_charles1@columbusstate.edu">Charles Little</a>, All rights reserved.</p>
            </div>
        </div>
    </nav>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/purl.min.js"></script>
    <script>
	$(document).ready(function($) {
		if ($.url().param('x')=='logout'){
			$('#logoutdiv').show();
		}
		if ($.url().param('x')=='403'){
			$('#noaccess').show();
		}
	});
	</script>

    </body>
</html>
