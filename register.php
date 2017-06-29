<?php
	session_start();
	$thispage = 'register';

	include_once 'config.php';
	include '_register_func.php';
	
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
    <link rel=stylesheet href="css/bootstrap-datepicker.min.css"/>
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
		    <?php include '_headers.php' ?>
			<?php include '_createuser.php' ?>		    
			<?php include '_login.php' ?>
			<?php include '_registrant.php' ?>
			<?php include '_residence.php' ?>

			<div id="final" name="final" class="jumbotron" hidden>
	            <h3>Thank you. Now what?</h3>
				<p>Your registration and residence information has been submitted for approval. Once approved, you'll receive a voter registration card and an email notification.</p>
	        </div>
	    </section>

	    
		<div class="progress">
			<div class="progress-bar progress-bar active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 5%;"><span id="progressbarvalue">5%</span>
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
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/jquery.maskedinput.min.js"></script>
	<script src="js/registrant.min.js"></script>
    </body>
</html>