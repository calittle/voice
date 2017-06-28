<?php 
	session_start();
	include 'ac.php'; 
	# pull out any needed registration variables.
	# note: for many more variables it's better to pull out the array
	# that way we don't have to access the session array more than once.
	# Example:
	#	$regdata = $_SESSION['regdata1'];
	#	$state   = $regdata['stateInput'];
	# But, for only a single variable or two, this is fine:
	$state = $_SESSION['regdata']['stateInput'];
	
	
	# get Affirmations and Parties.
	# state_parties_list(statecd)
	# get_affirmations(statecd)
	try {
			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			

			$stmt	= $pdo->prepare('CALL state_parties_list(?)');
			$stmt -> bindParam(1,$state);
			$parties = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$parties[]=$row;
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
			
			$stmt	= $pdo->prepare('CALL get_affirmations(?)');
			$stmt -> bindParam(1,$state);
			$affirms = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$affirms[]=$row;
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
	            <h3>Register to Vote (Page 3)</h3>
				<p>Almost done! You need to affirm the following statements for your chosen registration state<?=' ('.$state.')'?>, and if you wish, may select a political party affiliation.</p>
	        </div>	
			<div class="well well-lg">
				<form method="POST" action="registrant2_form.php">					
						<p class="help-block">States require registrants to meet certain qualifications to register to vote. By ticking the checkbox below, you swear or affirm that you meet these qualifications. Your selected registration state<?=' ('.$state.')'?> states that registrants must:</p>
						<?php
	print '<ul>';
	foreach ($affirms as $row){
		print '<li vid="'.$row['AFFIRM_ID'].'">'.$row['AFFIRMATION'].'</li>';
	}
	print '</ul>';
	?>	
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
								<option value="">-- Select Party --</option>
<?php
	foreach ($parties as $row){
		print '<option value="'.$row['PARTYCD'].'">'.$row['PARTY'].' ('.$row['PARTYCD'].')</option>';
	}
	?>

							</select>
						</div>
					</div>
				  <button type="submit" class="btn btn-default">Register!</button>
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
	    <div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 75%;">
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
	$('form').validate({
	    rules: {	        
	        partyInput: {required:false}
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
			url: "registrant2_form.php",
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
					console.log('Registrant created: ' + data);
					$('#formdiv').fadeOut(500);
					$('#errormessagediv').hide();
					window.location.href = "final.php";
				}
				else{
					console.log('Registration failure. Err=' + msg + '\n Data returned was:' + data);
					this.error(this.xhr,'System administrators have been notified.',' You can try again...');
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem with your registrant information. " + textStatus + errorThrown);
				$('#errormessagediv').show();		
				$inputs.prop("disabled",false)
			}
		});
		//request.done(function (response,textStatus,jqXHR){
		//	$('#formdiv').fadeOut(1000);
		//	$('#successdiv').show();
		//});
		request.fail(function (jqXHR, textStatus, errorThrown){
			$('#errormessage').html("There was a problem with your registrant information: " + textStatus + errorThrown);
			$('#errormessagediv').show();		
			$inputs.prop("disabled",false)
		});
		//request.always(function(){$inputs.prop("disabled",false)});
	});
});
</script>
    </body>
</html>
