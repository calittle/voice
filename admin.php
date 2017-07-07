<?php
	session_start();
	$thispage = 'admin';
	include_once 'ac.php';
	include_once 'roles.php';
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
    <script>
$(function() {
   $(document).on('click', '.alert-close', function() {
       $(this).parent().hide();
   })
});
var request;
function deleteUser(u){
	$('#errormessage').html('You do not have authority to delete users.');
	$('#errormessagediv').fadeIn(500);	
	$('#errormessagediv').fadeOut(3000);	
}
function revokeUserRole(u,r){
	$('#errormessage').html('');
	$('#errormessagediv').hide();
	if (request){request.abort();}
	var edata = "function=revokerole&par1=" + u + "&par2=" + r;
	var o;
	request = $.ajax({
			url: "ajx_functions.php",
			type: "post",
			data: edata,
			dataType: "json",
			success: function(data){
				var successMsg=false;
				var msg='';
				try{
					//o = JSON.parse(data);
					successMsg = data['success'];
					msg = data['message'];
				}catch(err){
					msg = err;
				}
				if (successMsg==true){
					console.log(data['function'] + ' results: ' + data['success']);
					$('#successmessage').html(msg);
					$('#successmessagediv').fadeIn(500);
					$('#successmessagediv').fadeOut(2500);
					$('#errormessagediv').hide();
				}
				else{
					console.log('Unable to execute ' + data['function'] + '. ' + msg + '. Data returned: [' + data + ']');
					this.error(this.xhr,'Unable to perform that request; ',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html(textStatus + errorThrown);
				$('#errormessagediv').fadeIn(500);	
			}
		});	

}
function addUserRole(u,r){
	$('#errormessage').html('');
	$('#errormessagediv').hide();
	if (request){request.abort();}
	var edata = "function=addrole&par1=" + u + "&par2=" + r;
	var o;
	request = $.ajax({
			url: "ajx_functions.php",
			type: "post",
			data: edata,
			dataType: "json",
			success: function(data){
				var successMsg=false;
				var msg='';
				try{
					//o = JSON.parse(data);
					successMsg = data['success'];
					msg = data['message'];
				}catch(err){
					msg = err;
				}
				if (successMsg==true){
					console.log(data['function'] + ' results: ' + data['success']);
					$('#successmessage').html(msg);
					$('#successmessagediv').fadeIn(500);
					$('#successmessagediv').fadeOut(2500);
					$('#errormessagediv').hide();
				}
				else{
					console.log('Unable to execute ' + data['function'] + '. ' + msg + '. Data returned: [' + data + ']');
					this.error(this.xhr,'Unable to perform that request; ',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html(textStatus + errorThrown);
				$('#errormessagediv').fadeIn(500);	
			}
		});	
}
</script>
    </body>
</html>
