<?php 
	session_start();
	$thispage='registrant';
	include 'ac.php'; 
	
	# note: if the user has returned from a previous page (e.g. history->back) 
	# we *could* repopulate from what was entered previously.
	# or we just let the browser handle it.
	
		try {
			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
			#country parameter is HARD CODED for this implementation.
			$queryState = 'USA';
			$stmt	= $pdo->prepare('CALL state_list(?)');
			$stmt -> bindParam(1,$queryState);
			$states = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$states[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
					switch ($errs[1]){
						default:
							error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
					}
				}
			}
			
			$stmt	= $pdo->prepare('CALL ethnicities_list()');						
			$eths = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$eths[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
						switch ($errs[1]){
							default:
								error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						}
				}
			}
			
			$stmt	= $pdo->prepare('CALL genders_list()');						
			$genders = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$genders[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
					switch ($errs[1]){
						default:
							error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
					}
				}
			}
			
			
		}catch (PDOException $e){			
			echo($e->getMessage());
			die();
		}
		finally{
			$stmt = null;
			$pdo = null;			
		}							
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
	<link rel="stylesheet" href="css/bootstrap-datepicker.min.css" />
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
	            <h3>Register to Vote (Page 2)</h3>
				<p>Now we need to know about you, the voter. Fill out the information below. </p>
	        </div>	
			<div class="well well-lg">
				<form method="POST" action="registrant_form.php">
					<div class="form-group">
				    	<label for="firstNameInput">First Name</label>
						<p class="help-block">Enter your first name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="firstNameInput" name="firstNameInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="middleNameInput">Middle Name</label>
						<p class="help-block">Enter your middle name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-question-sign"></span></span>
							<input type="text" class="form-control" id="middleNameInput" name="middleNameInput">
						</div>
					</div>
					<div class="form-group">
				    	<label for="lastNameInput">Last Name</label>
						<p class="help-block">Enter your Last name as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="lastNameInput" name="lastNameInput" required>					
						</div>
					</div>
					<div class="form-group">
				    	<label for="suffixNameInput">Suffix</label>
						<p class="help-block">Enter your name suffix (e.g. Jr, II, etc) as it appears on your government-issued identification or birth certificate.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-question-sign"></span></span>							
							<input type="text" class="form-control" id="suffixNameInput" name="suffixNameInput">
						</div>
					</div>
					<hr class="separator">
					<div class="form-group">
				    	<label for="phoneInput">Phone Number</label>
						<p class="help-block">Just in case we need to contact you about your registration request.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>							
							<input type="text" class="form-control" id="phoneInput" name="phoneInput">
						</div>
					</div>
					<div class="form-group">
				    	<label for="birthDateInput">Birth date</label>
						<p class="help-block">Enter your birthdate as YYYY-MM-dd, or use the picker.</p>
						<div class="input-group date">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>							
							<input type="text" class="form-control" id="birthDateInput" name="birthDateInput" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="stateIdInput">State ID Number</label>
						<p class="help-block">Enter state identification number (ex. Driver License number) </p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>							
							<input type="text" class="form-control" id="stateIdInput" name="stateIdInput" >
						</div>
					</div>
					<!-- uncomment if need to support another ID number (e.g. SSN, or some other scheme).-->
					<div class="form-group">
				    	<label for="fedIdInput">Last 4 digits of SSN</label>
						<p class="help-block">Please enter last four digits of your Social Security Number.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>							
							<input type="text" class="form-control" id="fedIdInput" name="fedIdInput" >
						</div>
					</div>
					
					<!-- Note: in a single-state implementation you could simply hard-code this value and not use this input -->
					<div class="form-group">
				    	<label for="stateInput">Registration State</label>
						<p class="help-block">Choose the state in which you are registering</p>
						<div class="input-group">
							<select class="form-control required" id="stateInput" name="stateInput" required>
								<option value="">-- Select State --</option>
<?php
	foreach ($states as $row){
		print '<option value="'.$row['State Code'].'">'.$row['State'].' ('.$row['State Code'].')</option>';
	}
	?>

							</select>
						</div>
					</div>
					<hr class="separator">					
					<div class="form-group">
				    	<label for="ethnicityInput">Ethnicity</label>
						<p class="help-block">This question is asked to comply with federal regulations and is optional.</p>
						<div class="input-group">
							<select class="form-control" id="ethnicityInput" name="ethnicityInput">
								<option value="UN" selected>-- Select Ethnicity --</option>
<?php
	foreach ($eths as $row){
		print '<option value="'.$row['ETHNICITYCD'].'">'.$row['ETHNICITY'].'</option>';
	}
	?>
							</select>
						</div>
					</div>
					<div class="form-group">
				    	<label for="genderInput">Gender</label>
						<p class="help-block">This question is asked to comply with federal regulations and is optional.</p>
						<div class="input-group">
							<select class="form-control" id="genderInput" name="genderInput">
								<option value="UNS" selected>-- Select Gender --</option>
<?php
	foreach ($genders as $row){
		print '<option value="'.$row['GENDERCD'].'">'.$row['GENDER'].'</option>';
	}
	?>
							</select>
						</div>
					</div>
									
					
				  <button type="submit" class="btn btn-default">Next</button>
				</form>
			</div>  
	    </section>
	    <div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
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
	
<script>
$(document).ready(function($) {
	
	$('#phoneInput').mask("(999) 999-9999");
	$('#birthDateInput').mask("9999-99-99",{placeholder:"yyyy-mm-dd}"});
	$('#fedIdInput').mask("9999");
	jQuery.validator.addMethod("lettersonly", function(value, element) {return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);}, "Letters/spaces only please.");
	$('.input-group.date').datepicker({
	    startView: 2,
	    format: "yyyy-mm-dd",
	    autoclose: true
	});
	$('form').validate({
	    rules: {
	        suffixNameInput: {
	            minlength: 1,
	            maxlength: 256,
	            required: false
	        },
	        lastNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: true
	        },
	        middleNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: false
	        },
	        firstNameInput: {
	            minlength: 3,
	            maxlength: 256,
	            lettersonly: true,
	            required: true		        
	        },
	        birthDateInput:{
		        minlength: 8,
		        maxlength: 10,
		        required: true
	        },
	        phoneInput: {required:false},
	        stateIdInput: {maxlength:256,required:false},
	        fedIdInput: {maxlength:4,required:true},
	        stateInput: {required:true}
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
});
</script>
    </body>
</html>
