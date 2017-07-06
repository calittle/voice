<?php 
	session_start();
	$thispage='vote';
	include 'ac.php'; 
	try {
			
			
		
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));
			$stmt = null;					
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
					# error_log('Election ('.$el['Election ID'].') Ballots('.count($results).')');
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
        <h1 class="sr-only">Vote</h1>
    </header>
	<div class="container">
		<div id="electionsdiv" name="electionsdiv" class="well well-lg">
			<h3>Elections</h3>
			<p>The table below lists election(s) for your district(s). Each row indicates a separate election, and shows the valid dates/times that the election is open. You can click on election to view details about the measures and options included for each election, and to open a ballot for the election. In the status column below, labels indicate the status of the election and your ballot for that election:
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
								<td><?=$row['Election Detail']?></td>
								<td><?=$row['Start Date']?></td>
								<td><?=$row['End Date']?></td>
							</tr>
			
			<?php			}
			?>
					</tbody>
				</table>
			</div>												
		</div>
		<?php
		
		?>
		<div id="electiondetaildiv" name="electiondetaildiv" class="well well-lg" hidden>
		</div>
		<div id="ballotdiv" name="ballotdiv" class="well well-lg" hidden>
			<div class="panel panel-primary">
			<div class="panel-heading">					
				<h3 id="ballothead" name="ballothead" class="panel-title">Election 1</h3>
				<p id="ballotdetail" name="ballotdetail">Election Detail</p>
			</div>
			<div class="panel-body">				
				<form name="ballotform" id="ballotform" method="post" action="ajx_cast.php">					
				</form>
				<!--
				<button id="castBallotModalButton" name="castBallotModalButton"  class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#confirmBallotModal" role="button">CAST BALLOT</button><br/>				-->
				<button id="closeBallotButton" name="closeBallotButton" onclick="closeBallot(1);" class="btn btn-primary btn-sm" role="button">Close Ballot</button>
		</div> <!-- ballot panel -->
		</div> <!-- ballot well -->
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
$(document).ready(function() {	
	
	var request;
	
	$('#ballotform').submit(function(event){
		event.preventDefault();
		if (!confirm("You are about to CAST your BALLOT in this election. Do you wish to continue?"))
			return;
		if (request){request.abort();}
		var $form = $(this);
		var $inputs = $form.find("input, button");
		var serialdata = $form.serialize();
		$inputs.prop("disabled",true);
		request = $.ajax({
			url: "ajx_cast.php",
			type: "post",
			data: serialdata,
			success: function(data){
				var o;
				var successValue=0;//0=false, 1=true
				var msg='';
				console.log(data);
				try{
					o = JSON.parse(data);
					successValue = o['success'];
				}catch(err){
					msg = err;
				}
				if (successValue==1){
					console.log('Ballot cast: ' + data);
					$inputs.prop("disabled",true);
					if (o['provisional']==1)
						postBallot("Your ballot was cast outside the timeframe of the election and has been marked provisional.");
					else
						postBallot("Your ballot was cast and stored in the system with your private key.");				}
				else{					
					//check if this ballot was already cast. In which case, not an error, but 
					if (o['code']=='1062'){
						$inputs.prop("disabled",true);
						postBallot("You have already cast a ballot for this election; this ballot cannot be submitted.");
					}else{
						console.log('Cast failure. Err=' + msg + '\n Data returned was:' + data);
						this.error(this.xhr,'System administrators have been notified.','Err=' + msg + '\n Data returned was:' + data);
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$('#errormessage').html("There was a problem casting your ballot. " + textStatus + errorThrown);
				$('#errormessagediv').show();		
				$inputs.prop("disabled",false)
			}
		});
	});

	$('#elections tr').click(function(e){
		if (request){request.abort();}
        var edata = "election=" + $(this).find("th").attr("id");
        var voted = $(this).find("th").attr("voted");
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
					injectDetails(o['elections'][0],voted);				
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
     // delegated handlers for click event on dynamically-injected elements.

     // HANDLER for RADIO elements to process WRITE-IN radio. 
     // Scope of selector must inclue all radio elements in this 
     // group so we can detect when it's necessary to hide the 
     // write-in elements.
     $('#ballotform').on('click','.injected',function() {
	 	var id = $(this).attr('id');
	 	var pxn = /(opt)\-[0-9]/g; //regex to select "opt-NNN" characters to replace.
	 	if (this.value == 'write-in'){
			$('#' + id.replace('opt-','')).fadeIn(500).addClass("required");	
		}else{
			$('#' + id.replace(pxn,"writein")).fadeOut(500).removeClass("required");	
		}
 
	});	
});
function injectBallot(o){
	// INPUT for electionId (hidden)
	var mIds = '';
	var s = '<input type="hidden" name="electionId" value="' + o.id + '"/>';
	// LOOP on available measures.
	o.measures.forEach(function (measure){
		// add MEASURE ID to special hidden input.
		mIds += measure.id + ',';
		// MEASURE detail output.
		s += '<div class="input-group" id="div-measure-' + measure.id + '" name="div-measure-' + measure.id + '"><p id="detail-measure-' + measure.id + '" name="detail-measure-' + measure.id + '">' + measure.detail + '</p>';
		// LOOP on available options.
		var oc = 1;
		// add NO-SELECT option.
		// REMOVED this because this should be provided in ballot management.
		//s += '<div class="radio"><label><input class="injected" type="radio" name="measure-' + measure.id + '" id="measure-' + measure.id + '-opt-0" value="nochoice" checked="checked">No choice (default)</option></label></div>';
		measure.options.forEach(function (option){
			s += '<div class="radio"><label><input class="injected" type="radio" name="measure-' + measure.id + '" id="measure-' + measure.id + '-opt-' + oc + '" value="' + option.id + '">' + option.detail + '</option></label></div>';
			oc += 1;
		});
		// output measure Ids		
		s +=  '<input type="hidden" name="measureIds" value="' + mIds.substring(0,mIds.length-1) + '"/>';
		// add WRITE-IN option.
		// RESERVED FOR FUTURE USE
		//s += '<div class="radio"><label><input class="injected" type="radio" name="measure-' + measure.id + '" id="measure-' + measure.id + '-opt-writein" value="write-in">Write-In</option></label></div>';
		//s += '<div id="measure-' + measure.id + '-writein" name="measure-' + measure.id + '-writein" class="form-group" hidden><label for="writein">Write-in</label><p class="help-block">Enter your write-in option.</p><div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span><input type="text" class="form-control" name="measure-' + measure.id + '-writein" id="measure-' + measure.id + '-writein"></div></div>';
		// close out MEASURE
		s += '</div></div>';
	});
	s += '<button type="submit" class="btn btn-primary btn-block">CAST BALLOT</button>';
	// INJECT ballot.
	$('#ballotform').html(s);
	$('#ballothead').html('Ballot for: ' + o.name);
	$('#ballotdetail').html(o.detail);
}
function injectDetails(o,voted){
	// parse election details and populate to div.	
	var s = '<h3>Ballot Details: ' + o.name + '</h3><p><div class="list-group">';
	o.measures.forEach(function (arrayItem){
		s += '<a class="list-group-item"><strong>' + arrayItem.detail + '</strong></a><div class="list-group">';
		arrayItem.options.forEach( function (arrayItem){
			s += '<a class="list-group-item">' + arrayItem.detail + '</a>';
		});
		s += '</div>';
	});
	if (voted>0)		
		s+='</div><em>You have already cast a ballot in this election. Check your <a href="account.php">account</a> to review ballots cast.</p>';
	else
		s+='</div><button id="showBallot" name="showBallot" onclick="openBallot();" class="btn btn-primary btn-lg btn-block" role="button">Open new ballot</button></p>';
		
	
	
	// call to inject selected ballot details into ballot divs, assuming it will be displayed (since we have the data now).
	injectBallot(o);
	
	$('#electiondetaildiv').html(s);
	$('#electiondetaildiv').fadeIn(500);	
}
function showBallot(){
	openBallot();
}
function openBallot(){
	// display ballot & hide other stuff.
	$('#ballotdiv').fadeIn(500);
	$('#electionsdiv').fadeOut(400);
	$('#electiondetaildiv').fadeOut(450);
}
function closeBallot(c){
	//c(heck) option allows for confirmed close. Pass value for c to confirm closure.
	if (c){
	if (confirm('Do you wish to abandon this ballot?\n\nIf you wish to stay and complete your ballot, click CANCEL, then complete the ballot and click CAST BALLOT.')){
		//$('#ballotdiv').fadeOut(400);
		//$('#electionsdiv').fadeIn(500);
		location.reload();
	};
	}else{
	//	$('#ballotdiv').fadeOut(400);
	//	$('#electionsdiv').fadeIn(500);		
	location.reload();
	}
}
function postBallot(msg){	
	$('#successmessage').html(msg);
	$('#successmessagediv').fadeIn(500);						
	document.getElementById('closeBallotButton').onclick = function(){ closeBallot(); };
	
	//closeBallot();

}
</script>
    </body>
</html>
