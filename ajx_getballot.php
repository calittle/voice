<?php 
    
	session_start();	
	
	require_once 'ac.php';	

	$eId = isset($_POST['electionId']) ? $_POST['electionId'] : null;			
	
	$uId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
	$rId = isset($_SESSION['rid']) ? $_SESSION['rid'] : null;
	
	$errors = 0;
	$ret = array();
	$ret['success'] = false;
	$ballot = array();
	try
	{			
		$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		
		// we do this here to prevent session-spoofing; verifying that the logged-in user can actually access this election.
		$stmt	= $pdo->prepare('CALL receipt_generate(?,?)');		
		$stmt -> bindParam(1,$rId);
		$stmt -> bindParam(2,$eId);
		error_log('Call receipt gen '.$rId.','.$eId);
		if ($stmt->execute())
		{ 
			// loop through ballot contents.
			$c = 1;
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
			{				
				error_log ($c.' In here ');
				$ret['ballots'][$c]['measure'] = $row['Measure'];
				$ret['ballots'][$c]['measureid'] = $row['Measure ID'];
				$ret['ballots'][$c]['choice']  = $row['Chosen Option'];
				$ret['ballots'][$c]['choiceid']= $row['Chosen ID'];
				$ret['ballots'][$c]['casttime']= $row['Cast Time'];
				$ret['ballots'][$c]['counted'] = $row['Counted']==0 ? false : true;
				$ret['ballots'][$c]['counttime']=empty($row['Count Time']) ? null : $row['Count Time'];
				$ret['ballots'][$c]['provisional']=$row['Provisional']==0 ? false : true;
				$ret['ballots'][$c]['check'] = validateVote()==true ? 'valid' : 'invalid';
				$c++;
			}
			$c--;
			$ret['count']=$c;
		}else
		{
			$errs = $stmt->errorInfo();	

			if (!empty($errs[1]))
			{						
				switch ($errs[1])
				{
					default:
						$ret['message'] = 'Unable to get list of elections. System administrators have been notified.';
						$errors++;
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 							
				}
			}
		}												
	}catch (PDOException $e)
	{
		echo($e->getMessage());
		
	}

	if ($errors>0)
	{
		$ret['success'] = 0;			
	}else
	{
		$ret['success'] = 1;
	}


#	foreach ($ret as $key => $value){
#		error_log('Response Message: Key ['.$key.'] Value ['.$value.']');
#	}


	$stmt = null;
	$pdo = null;		
	$retval = json_encode($ret);
	if (!$retval){
		$ret = array();
		$ret['success']=0;
		$ret['message']="Unable to JSON-encode return value (".json_last_error().")";
	}else{
		echo $retval;
	}
?>