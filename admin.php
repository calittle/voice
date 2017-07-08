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
						<p>Click on a <span class="label label-default">role</span> label to grant the role to the user. Click on a <span class="label label-success">colored role</span> to revoke that role from a user.</p>
						<div class="table-responsive">
							<table id="usertable" name="usertable" class="table table-hover">
								<thead><tr><th>ID</th><th>Action</th><th>Username</th><th>Email</th><th>Roles</th></tr></thead>
								<tbody>	
<?php 
	userList();
?>										
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div id="registrants" class="tab-pane fade in out">
					<div class="well well-lg">
						<h4>Registrants</h4>
						<p>Process approval by clicking on <span class="label label-default">Approve</span> or <span class="label label-default">Reject</span> label.</p>
						<div class="table-responsive">
							<table id="usertable" name="usertable" class="table table-hover">
								<thead><tr>
										<th>ID</th>
										<th>UID</th>
										<th>Name</th>
										<th>DoB</th>
										<th>Phone</th>
										<th>State ID</th>
										<th>Fed. ID</th>
										<th>Party</th>
										<th>State</th>
										<th>Appr.</th>
										<th>Affirm</th></tr>
								</thead>
								<tbody>	
<?php 
	registrantList();
?>										
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
								
				<div id="districts" class="tab-pane fade in">
					<div class="well well-lg">
						<h4>Districts</h4>	
						<p>Select a district from the dropdown, and then use the adjacent text box to update it, then click <kbd>Update District</kbd>, or click <kbd>Delete District</kbd>. To create a new District, enter a name in the indicated text box, and click <kbd>Add New District</kbd>.</p><br/>
						<div class="row">
							<div class="col-md-4">
							<form name="editDistrictForm" id="editDistrictForm">
								<label for="districtIdInput">Select a District</label>
								<select name="districtIdInput" id="districtIdInput">
									<option value="" selected>--Select District--</option>
									<?php echo districtList();?>
								</select>
								<label for="editDistrictInput">Edit District</label>
								<input type="text" id="editDistrictInput" name="editDistrictInput">
							</form>
							<button class="btn btn-default" id="updateDistrictButton" name="updateDistrictButton" type="button">Update District</button>								
							<button class="btn btn-default" id="deleteDistrictButton" name="deleteDistrictButton" type="button">Delete District</button>
							</div>
							<div class="col-md-8">
								<form name="newDistrictForm" id="newDistrictForm" method="" action="post">
								<label for="newDistrictInput">Create a New District</label>
								<input type="text" id="newDistrictInput" name="newDistrictInput">
								</form>
								<button class="btn btn-default" id="newDistrictButton" name="newDistrictButton" type="button">Add New District</button>								
							</div>
						</div>						
					</div>
					<div class="well well-lg">
						<h4>Registrant - Districts</h4>					
						<div class="row">
							<div class="col-md-12">
<!--nav aria-label="Page navigation">
  <ul class="pagination">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav-->
								<div class="table-responsive">
									<table id="registrantDistrictTable" name="registrantDistrictTable" class="table table-hover">
										<thead><tr>
												<th>Registrant</th>
												<th>District(s)</th>
												<th>Action</th>
												</tr>
										</thead>
										<tbody>	
										<?php 
											registrantDistrictList();
										?>										
										</tbody>
									</table>
								</div><!--
								<form name="registrantDistrictForm" id="registrantDistrictForm">
									<label for="registrantIdInput">1. Select a Registrant</label>
									<select name="registrantIdInput" id="registrantIdInput">
										<option value="" selected>--Select Registrant--</option>
									</select>
									<br/>
									<label for="registrantIdInput">2. Select a District to REMOVE from Registrant</label>
									<select name="regDistrictDeleteInput" id="regDistrictDeleteInput">
										<option value="" selected>--Select from Registrant's Districts--</option>
									</select>
									<br/>									
									<label for="registrantIdInput">3. Select a District to ADD to Registrant</label>
									<select name="regDistrictAddInput" id="regDistrictAddInput">
										<option value="" selected>--Select from available Districts--</option>
										<?php echo districtList();?>
									</select><br/>
									<label for="updateRegistrantDistrictButton">4. Click Update to process change(s).</label><button class="btn btn-default" id="updateRegistrantDistrictButton" name="updateRegistrantDistrictButton" type="button">Update</button>		-->						
							</form>
							</div>
						</div>						
					</div>
				</div>
				
								
				<div id="elections" class="tab-pane fade in">
					<div class="well well-lg">
						<h4>Elections</h4>
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
											case 'regdata':
												$val = 'Array:<br/>';
												foreach ($value as $subkey => $subvalue){
													$val += $subkey.' = '.$subvalue.'<br/>';
												}
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
		</div>      
	    <div class="alert alert-danger alert-dismissible" role="alert" id="errormessagediv" hidden>
		    <button type="button" class="close alert-close" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<span id="errormessage"></span>
	    </div>
	    <div class="alert alert-success alert-dismissible" role="alert" id="successmessagediv" hidden>
		    <button type="button" class="close alert-close" aria-label="Close">
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
    <script src="js/voice.functions.js"></script>
    <script>
$(document).ready(function($) {
			
   $(document).on('click', '.alert-close', function() {
       $(this).parent().hide();
   });
   $(document).on('click','#deleteDistrictButton', function(){
	   var did = $("#districtIdInput").val();
	   var dname = $("#districtIdInput option:selected").text()
	   if (confirm('Are you sure you wish to delete district [' + dname + ']?'))
	   		deleteDistrict(did);
   });
   $(document).on('click','#updateDistrictButton', function(){
	   var d =$('#editDistrictInput').val();
	   var did = $("#districtIdInput").val();
	   if (d.length >= 3 & d.length <= 128)
	   		if (confirm('Are you sure you want to change district '+ did + ' to ' + d + '?'))
	   			updateDistrict(did,d);
		else
			alert('District name must be 3-128 characters');
   });
   $(document).on('click','#newDistrictButton', function(){
	   var d =$('#newDistrictInput').val();
	   if (d.length >= 3 & d.length <= 128)
	   		addDistrict(d);
		else
			alert('District name must be 3-128 characters');
   });
   $('#districtIdInput').on('change', function() {
   		$('input[name="editDistrictInput"]').val($("#districtIdInput option:selected").text());
	})
   $('.registrantAddDistrictInput').on('change', function() {
	   addDistrictToRegistrant( $(this).attr("id"),$(this).val());
	})
   
});
</script>
    </body>
</html>
