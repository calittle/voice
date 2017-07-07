<?php 

	session_start();
	$thispage='account';
	include 'ac.php'; 
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

			$stmt	= $pdo->prepare('CALL registrant_get_districts(?)');
			$stmt -> bindParam(1,$_SESSION['rid']);
			$districts = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$districts[]=$row;
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
			
			$stmt	= $pdo->prepare('CALL user_roles(?,?)');

			$stmt -> bindParam(1,$_SESSION['uid']);
			$stmt -> bindParam(2,$_SESSION['username']);
			$roles = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$roles[]=$row;
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
		
			
			$stmt	= $pdo->prepare('CALL registrant_get_elections(?)');
			$stmt -> bindParam(1,$_SESSION['rid']);
			$elections = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$elections[]=$row;
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
			
			$ballots = array();			
			foreach($elections as $el){
				$stmt = null;					
				$stmt	= $pdo->prepare('CALL receipt_generate(?,?)');
				$stmt -> bindParam(1,$_SESSION['rid']);
				$stmt -> bindParam(2,$el['Election ID']);
				$results = array();
				if ($stmt->execute()){
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$results[]=$row;
					}
					#error_log('Election ('.$el['Election ID'].') Ballots('.count($results).')');
					$ballots[$el['Election ID']]['ballots']=count($results);
				}else{
					$errs = $stmt->errorInfo();	
					if (!empty($errs[1])) {						
						switch ($errs[1]){
							default:							
								error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						}
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
    <style>

#elections td:hover {
    cursor: pointer;
}
	    </style>
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
        <h1 class="sr-only">Account</h1>
    </header>
	<div class="container">
	    <section>
		    <ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#user">User</a></li>		    
			  <li><a data-toggle="tab" href="#registrant">Registrant</a></li>
			  <li><a data-toggle="tab" href="#locations">Residence</a></li>
	  		  <li><a data-toggle="tab" href="#districts">Districts</a></li>
			  <li><a data-toggle="tab" href="#voting">Elections</a></li>  		  
	  		  <li><a data-toggle="tab" href="#debug">Debug</a></li>
			</ul>
			<div class="tab-content">
				<div id="user" class="tab-pane fade in active">
					<div class="well well-lg">
						<ul>
						<?php 
							echo '<li>User Name: '.$_SESSION['username'].'</li>'
							
						?>
						</ul>
					</div>
					<div class="well well-lg">
						<h3>Roles</h3>
							<?php 
							if (count($roles)<1){
								echo "<p>There doesn't seem to be anything here.</p>";
							}else{
								echo '<ul>';
								foreach ($roles as $row){
									echo '<li>'.$row['ROLE'].'</li>';
								}
								echo '</ul>';
							}
								?>
					</div>
					<div class="well well-lg">
						<h3>Change Email/Password</h3>
						<form name="passwordform" id="passwordform" method="post" action="">
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
							<span class="label label-default" id="passstrength" name="passstrength"></span>
						  </div>						  
						  <button type="submit" class="btn btn-default">Update</button>						  
						</form>
					</div>					
				</div>				
				<div id="voting" class="tab-pane fade in">
					<div class="well well-lg">
						<h3>Elections</h3>
			<p>The table below lists election(s) for your district(s). Each row indicates a separate election, and shows the valid dates/times that the election is open. You can click on election to view details about the measures and options included for each election. In the status column below, labels indicate the status of the election and your ballot for that election:
							<ul>
					<li>Elections in which you have <em>not cast</em> a ballot will display a <span class="label label-danger">Not Voted</span> label</li>
					<li>Elections in which you have cast a ballot will display a <span class="label label-success">Voted</span> label</li>
					<li>Elections that are still open for voting will show a <span class="label label-info">Open</span> label</li>
					<li>Elections that are closed for voting will show a <span class="label label-warning">Closed</span> label</li>
				</ul>

</p>
						<div class="table-responsive">
							<table id="elections" name="elections" class="table table-hover table-striped ">
								<thead><tr><th>Status</th><th>Election Name</th><th>Details</th><th>Start Date</th><th>End Date</th></tr></thead>
								<tbody>						
						<?php	
									foreach ($elections as $row){
						?>		
										<tr>
											<th id="<?=$row['Election ID']?>" voted="<?=$ballots[$row['Election ID']]['ballots']?>" scope="row">
											<?php
												$electionBegin = strtotime(date($row['Start Date']));
												$electionEnd = strtotime(date($row['End Date']));
												$currentDateTime = date('Y-m-d H:i:s');
												if ($currentDateTime > $electionBegin && $currentDateTime < $electionEnd){
													echo ' <span class="label label-info">Open</span> ';
												}else{
													echo ' <span class="label label-warning">Closed</span> ';
												}
												if ($ballots[$row['Election ID']]['ballots']==0){
													echo ' <span class="label label-danger">Not Voted</span> ';
												}else{
													echo ' <span class="label label-success">Voted</span> ';
												}													
												?>											
											</th>
											<td><?=$row['Election Name']?></td>
											<td><?=$row['Election Detail']?>
											</td>
											<td><?=$row['Start Date']?></td>
											<td><?=$row['End Date']?></td>
										</tr>
						
						<?php			}
						?>
								</tbody>
							</table>
						</div>												
					</div>
					<div id="electiondetaildiv" name="electiondetaildiv" class="well well-lg" hidden>
						<h3>Election Detail</h3>
						<p id="electiondetail" name="electiondetail"></p>
					</div>
					<div id="ballotdiv" name="ballotdiv" class="well well-lg" hidden>						
					</div>
					
					
				</div>
				
				<div id="districts" class="tab-pane fade in ">
					<div class="well well-lg">
						<h3>Districts</h3>
						<ul>
							<?php foreach ($districts as $row){
								echo '<li>'.$row['DISTRICT'].'</li>';
							}?>
						</ul>
					</div>
				</div>
				
				<div id="registrant" class="tab-pane fade in">
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
						<div class="table-responsive">
							<table class="table table-hover">
								<thead><tr><th>Variable</th><th>Value</th></tr></thead>
								<tbody>			
								<?php	foreach ($_SESSION as $key => $value){

										switch ($key){
											case 'privkey':
											case 'pubkey':
												$val = empty($value) ? 'EMPTY' : 'HIDDEN';
												break;
											default:
												$val = $value;										
										}
										echo '<tr><td>'.$key.'</td><td>'.$val.'</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>
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
											<td><a href="#" aria-label="Edit Location" onclick="confirm('If you edit your address, your registration will be changed to unapproved state. You will have to wait until your registration is re-approved before you can vote again. Do you wish to continue?');">
												<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>&nbsp;
												<a href="#" onclick="confirm('If you remove your address, your registration will change to unapproved state.  You will be unable to vote until you add another address and your registration is confirmed. Do you wish to continue? (Ref:<?=$row['Location ID']?>)');" aria-label="Delete Location">
													<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Remove</a>
											</td>
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
				</div>
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
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
<script>
$(document).ready(function($) {	
	var request;
	$('#elections tr').click(function(e){
		if (request){request.abort();}        
        var edata = "election=" + $(this).find("th").attr("id");
        var voted = $(this).find("th").attr("voted");
        $(this).prop("disabled",true);
        $('#electiondetaildiv').hide();	
        $('#ballotdiv').hide();
        if (voted>0){

	        request = $.ajax({
				url: "ajx_getballot.php",
				type: "post",
				data: edata,
				success: function(data){
					var o;
					var sucksess=false;
					var msg='';
					try{
						o = JSON.parse(data);
						sucksess = o['success'];
					}catch(err){
						msg = err;
					}
					if (sucksess==true){					
						console.log('Ballot retrieved: ' + data);
						$('#ballotdiv').html(populate_ballot(o,o['count']));
						$('#ballotdiv').fadeIn(500);					
					}
					else{
						console.log('Unable to retrieve election data. Err=' + msg + '\n Data returned was:' + data);
						this.error(this.xhr,'Unable to retrieve election data: ',msg);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					$('#errormessage').html("There was a problem processing your request and System administrators have been notified. (" + textStatus + errorThrown + ")");
					$('#errormessagediv').fadeIn(500);		
				}			
			});
	        
        }else{
			request = $.ajax({
				url: "ajx_election.php",
				type: "post",
				data: edata,
				success: function(data){
					var o;
					var sucksess=false;
					var msg='';
					try{
						o = JSON.parse(data);
						sucksess = o['success'];
					}catch(err){
						msg = err;
					}
					if (sucksess==true){					
						console.log('Election retrieved: ' + data);
						$('#electiondetail').html(populate_election(o['elections'][0],voted));
						$('#electiondetaildiv').fadeIn(500);					
					}
					else{
						console.log('Unable to retrieve election data. Err=' + msg + '\n Data returned was:' + data);
						this.error(this.xhr,'Unable to retrieve election data: ',msg);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					$('#errormessage').html("There was a problem processing your request and System administrators have been notified. (" + textStatus + errorThrown + ")");
					$('#errormessagediv').fadeIn(500);		
				}			
			});
		}
     });
	$('#passwordInput').keyup(function(e) {
	     var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	     var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	     var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	     if (false == enoughRegex.test($(this).val())) {
	             $('#passstrength').html('Weak password. Please enter more characters.');
	     } else if (strongRegex.test($(this).val())) {
	             $('#passstrength').removeClass().addClass('label label-success');
	             $('#passstrength').html('Good password.');
	     } else if (mediumRegex.test($(this).val())) {
	             $('#passstrength').removeClass().addClass('label label-warning');
	             $('#passstrength').html('Ok password. Try mixed case and special characters.');
	     } else {
	             $('#passstrength').removeClass().addClass('label label-danger');
	             $('#passstrength').html('Weak password. Add special characters, mixed case, and numbers.');
	     }
	     return true;
	});
	
	$('#passwordform').validate({
	    rules: {
	        emailInput: {
	            minlength: 5,
	            maxlength: 256,
	            required: false
	        },
	        passwordInput: {
	            minlength: 8,
	            maxlength: 64,
	            required: false
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

	$('#locationform').validate({
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

	$('#passwordform').submit(function(event){
		if ($('#passwordform').valid()!=true){return false;}	
		event.preventDefault();
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "ajx_user.php",
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
					console.log('User updated: ' + data);
					$('#successmessagediv').fadeIn(250);
					$('#successmessagediv').fadeOut(5000);
					$('#successmessage').html("User successfully updated.");
					$inputs.prop("disabled",false)
				}
				else{
					console.log('User update failure. Err=' + msg + '\n Data returned was:' + data);
					this.error(this.xhr,'Unable to update user: ',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem processing your request and System administrators have been notified. (" + textStatus + errorThrown + ")");
				$('#errormessagediv').show();		
				$inputs.prop("disabled",false)
			}
		});
	});
	$('#locationform').submit(function(event){
		if ($('#locationform').valid()!=true){return false;}	
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
function populate_ballot(o,c){
	var s = '<h3>Your Ballot Details</h3><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Measure</th><th>Choice</th><th>Cast Time</th><th>Counted?</th><th>Provisional?</th><th>Validity Check</th></tr></thead><tbody>';	
	for (var i = 1; i <= c; i++)
	{
		s += '<tr><td>' + o.ballots[i].measure + '</td><td>' + o.ballots[i].choice + '</td><td>' + o.ballots[i].casttime + '</td><td>' + o.ballots[i].counted + '</td><td>' + o.ballots[i].provisional + '</td><td>' + o.ballots[i].check + '</td></tr>';
	}
	
	s += '</tbody></table>';
	return s;
}
function populate_election(o,voted){
// parse election details and populate to div.	
	// get appropriate election (id = eid);
	//	o['elections'],eid
	
	var s = '<ol>';
	o.measures.forEach(function (arrayItem){
		s += '<li>' + arrayItem.detail + '<ul>';
		arrayItem.options.forEach( function (arrayItem){
			s += '<li>' + arrayItem.detail + '</li>';
		});
		s += '</ul></li>';
	});	
	if (voted>0)		
		s+='</ol><hr/><strong>You have already voted in this election.</strong>';
	else
		s+='</ol><hr/><strong>To vote in this election, go to the <a href="vote.php">vote</a> page!</strong>';
	return s;
}
</script>
    </body>
</html>
