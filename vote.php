<?php 
	session_start();
	$thispage='vote';
	include 'ac.php'; 
	try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));
					
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
        <h1 class="sr-only">Vote</h1>
    </header>
	<div class="container">
	    <section>
			<div class="well well-lg">
				<h3>Elections</h3>
				<p>The table below lists election(s) you may access. Select a row to view its details. Some details you can see:
					<ul>
						<li>Elections in which you have <em>not cast</em> a ballot will display a <span class="label label-danger">Not Voted</span> label</li>
						<li>Elections in which you have cast a ballot will display a <span class="label label-success">Voted</span> label</li>
						<li>Elections that are still open for voting will show a <span class="label label-info">Open</span> label</li>
						<li>Elections that are closed for voting will show a <span class="label label-warning">Closed</span> label</li>
					</ul>
</p>
				<div class="table-responsive">
					<table id="elections" name="elections" class="table table-hover table-striped ">
						<thead><tr><th>ID</th><th>Election Name</th><th>Details</th><th>Start Date</th><th>End Date</th></tr></thead>
						<tbody>						
				<?php	
							foreach ($elections as $row){
				?>		
								<tr>
									<th id="<?=$row['Election ID']?>" scope="row"><?=$row['Election ID']?></th>
									<td><?php
										echo $row['Election Name'];
										if (empty($row['Registrant Ballots'])){
											echo ' <span class="label label-danger">Not Voted</span>';
										}else{
											echo ' <span class="label label-success">Voted</span>';
										}
									?></td>
									<td><?=$row['Election Detail']?>
									<?php
										$electionBegin = strtotime(date($row['Start Date']));
										$electionEnd = strtotime(date($row['End Date']));
										$currentDateTime = date('Y-m-d H:i:s');
										if ($currentDateTime > $electionBegin && $currentDateTime < $electionEnd){
											echo '<span class="label label-info">Open</span>';
										}else{
											echo '<span class="label label-warning">Closed</span>';
										}	
										?></td>
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
		$(this).prop("disabled",true);
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
					$('#electiondetail').html(populate_election(o['elections'][0]));
					$('#electiondetaildiv').show();					
				}
				else{
					console.log('Unable to retrieve election data. Err=' + msg + '\n Data returned was:' + data);
					this.error(this.xhr,'Unable to retrieve election data: ',msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem processing your request and System administrators have been notified. (" + textStatus + errorThrown + ")");
				$('#errormessagediv').show();		
			}
		});
     });
});
function populate_election(o){
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
	s+='</ol><a class="btn btn-primary btn-lg btn-block" href="ballot.php" role="button">Open Ballot</a>';
	return s;
}

</script>
    </body>
</html>
