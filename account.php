<?php 
	session_start();
	$thispage='account';
	include 'ac.php'; 
	
	/*
		*/
	try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));

			$stmt	= $pdo->prepare('CALL registrant_get_basic(?)');
			$stmt -> bindParam(1,$_SESSION['rid']);
			$registrant = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$registrant[]=$row;
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

			# See if there are any locations.					
						

			$stmt	= $pdo->prepare('CALL registrant_get_addresses(?)');
			$stmt -> bindParam(1,$_SESSION['rid']);
			$locations = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$locations[]=$row;
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
	    <ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#registrant">Registrant</a></li>
		  <li><a data-toggle="tab" href="#locations">Residence</a></li>
  		  <li><a data-toggle="tab" href="#debug">Debug</a></li>
		</ul>
		
		<div class="tab-content">
		  <div id="registrant" class="tab-pane fade in active">
			<div class="well well-lg">
				<h3>Registrant Data</h3>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead><tr><th>Name</th><th>DOB</th><th>Phone</th><th>State ID</th><th>Fed. ID</th><th>Gender</th><th>Ethnicity</th><th>Approval State</th><th>Affirmations</th><th>Party</th><th>State</th></tr></thead>
						<tbody>						
				<?php	
							foreach ($registrant as $row){
				?>		
								<tr>
									<th scope="row"><?=$row['Name']?></th>
									<td><?=$row['Date of Birth']?></td>
									<td><?=$row['Phone']?></td>
									<td><?=$row['State ID']?></td>
									<td><?='xxx-xx-'.$row['Federal ID']?></td>
									<td><?=$row['Gender']?></td>
									<td><?=$row['Ethnicity']?></td>
									<td><?=$row['Approval State']==1?'Approved':$row['Approval State']==2?'Rejected':'Pending Approval'?></td>
									<td><?=$row['Affirmation State']==1?'Affirmed':'Not Affirmed'?></td>
									<td><?=$row['Party Affiliation']?></td>
									<td><?=$row['State']?></td>									
								</tr>
				
				<?php			}
				?>
						</tbody>
					</table>
				</div>
			</div>
		  </div>
  		  <div id="debug" class="tab-pane fade">
			<div class="well well-lg">
				<h3>Session Variables</h3>
		    <?=print_r($_SESSION)?>
			</div>
  		  </div>

		  <div id="locations" class="tab-pane fade">
			<div class="well well-lg">
				<h3>Current Locations</h3>
				<p>You need to have at least one registered location. Optionally you can add a separate mailing address for temporary usage.</p>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead><tr><th>#</th><th>Actions</th><th>Location</th><th>County</th><th>Type</th></tr></thead>
						<tbody>						
				<?php	
							foreach ($locations as $row){
				?>		
								<tr>
									<th scope="row"><?=$row['Location ID']?></th>
									<td><a href="#" aria-label="Edit Location"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a> <a href="#" onclick="confirm('Remove <?=$row['Location ID']?>');" aria-label="Delete Location"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove</a></td>
									<td><?=$row['Address Line 1']?>, <?=$row['Address Line 2']?>, <?=$row['CSZ']?></td>
									<td><?=$row['County']?></td>
									<td><?=$row['Residence Type']?></td>
								</tr>
				
				<?php			}
				?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="well well-lg">
				<h3>Add a Location</h3>
				<form method="POST" action="locations_form.php">					
					<p class="help-block">You need to add, at a minimum, a residence location. You can also add a mailing location if you need a temporary mailing address.</p>
					<div class="form-group">
				    	<label for="street1Input">Address Line 1</label>
						<p class="help-block">Enter the first line of your address, e.g. street number and name.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="street1Input" name="street1Input" required>
						</div>
					</div>
					<div class="form-group">
				    	<label for="street2Input">Address Line 2</label>
						<p class="help-block">Enter the second line of your address, e.g. apartment number.</p>
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
							<input type="text" class="form-control" id="street2Input" name="street2Input" required>
						</div>
					</div
					<hr/>
					<button type="submit" class="btn btn-default">Add Location!</button>
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
	        street1Input: {required:true},
	        street2Input: {required:false}
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
