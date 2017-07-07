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
			<?php 
				$flagger=0;
				include '_headers.php';
				if (!isset($_SESSION['uid'])){ 
					include '_createuser.php';
					include '_login.php';
					$flagger = $flagger + 1;
				}
				if (!isset($_SESSION['rid'])){
					include '_registrant.php';
					$flagger = $flagger + 2;
				}
				if (!isset($_SESSION['lid'])){
					include '_residence.php';
					$flagger = $flagger + 4;
				}
			?>
			<div id="alreadyregistered" name="alreadyregistered" class="jumbotron" hidden>
	            <h3>You're already registered.</h3>
				<p>Maybe you want to check your <a href="account.php">account</a>?</p>
	        </div>
			<div id="final" name="final" class="jumbotron" hidden>
	            <h3>Thank you. Now what?</h3>
				<p>Your registration and residence information has been submitted for approval. Once approved, you'll receive a voter registration card and an email notification.</p>
				<h3>But Wait, There's More!</h3>
				<p>For <strong>testing purposes only</strong>, your account been automatically <em>approved</em> and you have been added to a <em>testing</em> district. This means that you can complete a sample ballot by proceeding to the <a href="#">vote</a> page, or looking for applicable elections in your <a href="account.php">account</a>. Once your account has been added to an actual district, you'll be able to complete a real ballot.</p>
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
	<script src="js/registrant.js"></script>
	<script>
	$( document ).ready(function() {
		setHeader(<?=$flagger?>);
	});			
	</script>
    </body>
</html>