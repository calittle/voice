<?php
	session_start();
	$thispage = 'admin';
	include_once 'ac.php';
	include_once 'roles.php';
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
		<div class="well well-lg media">
            <h1>VOICE Admin Site</h1>
            <ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#users">Users</a></li>		    
			  <li><a data-toggle="tab" href="#registrants">Registrants</a></li>
	  		  <li><a data-toggle="tab" href="#districts">Districts</a></li>
			  <li><a data-toggle="tab" href="#elections">Elections</a></li>  		  
	  		  <li><a data-toggle="tab" href="#debug">Debug</a></li>
			</ul>

			<div class="tab-content">
				<div id="users" class="tab-pane fade in active">
					<div class="well well-lg">
						<h4>Users</h4>
					</div>
				</div>
				
				<div id="registrants" class="tab-pane fade in out">
					<div class="well well-lg">
						<h4>Registrants</h4>
					</div>
				</div>
				
								
				<div id="districts" class="tab-pane fade in">
					<div class="well well-lg">
						<h4>Districts</h4>
					</div>
				</div>
				
								
				<div id="elections" class="tab-pane fade in">
					<div class="well well-lg">
						<h4>Elections</h4>
					</div>
				</div>
												
				<div id="debug" class="tab-pane fade in">
					<div class="well well-lg">
						<p><?=print_r($_SESSION)?>
					</div>
				</div>
		</div>      
	    <div class="alert alert-danger alert-dismissible" role="alert" id="errormessagediv" hidden>
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<span id="errormessage"></span>
	    </div>
	    <div class="alert alert-success alert-dismissible" role="alert" id="successmessagediv" hidden>
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Success:</span>
			<span id="successmessage"></span>
	    </div>
		<nav id="pagefooter" class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
	        <div class="container">
	            <div class="col-xs-12 text-center navbar-text">
					<p class="text-muted">Copyright &copy; 2017 <a href="mailto:little_charles1@columbusstate.edu">Charles Little</a>, All rights reserved.</p>
	            </div>
	        </div>
		</nav>
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
    </body>
</html>
