<?php 
    
	session_start();	
	
	require_once 'ac.php';	

	$eId = isset($_POST['electionId']) ? $_POST['electionId'] : null;			
	$uId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
	$rId = isset($_SESSION['rid']) ? $_SESSION['rid'] : null;

	#prepare data for signing.
	$data = 'ElectionId='.$eId.'&UserId='.$uId.'&RegistrantId='.$rId.'&MeasureIds='.$_POST['measureIds'];
	#error_log($data);
	
	# list of mIds from submitted ballot in POST.
	$measures = explode(",",$_POST['measureIds']);
	
	$errors = 0;
	$ret = array();
	$ret['success'] = false;
	
	try
	{			
		$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		
		// we do this here to prevent session-spoofing; verifying that the logged-in user can actually access this election.
		$stmt	= $pdo->prepare('CALL registrant_get_elections(?)');		
		$stmt -> bindParam(1,$rId);			

		if ($stmt->execute())
		{ 
			// loop through user's elections...
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
			{
				#error_log($row['Election ID'].'<-- election Id');				
				// we only get the election details for the selected election passed from POST.					
				if ($row['Election ID'] == $eId)
				{	
					// We have a valid election. 
					#error_log('We have a valid election :'.$eId);
					// If the election is CLOSED, set the provisional flag.
					$electionStartDate = strtotime(date($row['Start Date']));
					$electionEndDate   = strtotime(date($row['End Date']));
					$ret['ElectionId'] = $eId;
					$ret['RegistrantId'] = $rId;
					$ret['electionStart'] = $electionStartDate;
					$ret['electionStop'] = $electionEndDate;
					$ret['measures'] = count($measures);
					$measurecounter = 1;
					# loop through submitted measures, and commit ballot.
					foreach ($measures as $mId)
					{
						$ret['MeasureId'] = $mId;

						
						# future support for WRITE-IN process.
						$selectedValue = $_POST['measure-'.$mId]=='write-in' ? $_POST['measure-'.$mId.'-writein'] : $_POST['measure-'.$mId];
						$optionvalue = null;
						
						#error_log('Measure: '.$mId.'='.$selectedValue);
						
						#$ret['SELECTED_mId'.$mId] = $selectedValue;
						$currentDateTime   = date('Y-m-d H:i:s');
						if ($currentDateTime > $electionStartDate && $currentDateTime < $electionEndDate)
						{
							$provisional = 0;
						}else{
							$provisional = 1;
						}
						$results['measure_'.$measurecounter.'_provisional'] = $provisional;
							
						# create ballot data string.
						$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue;
						#error_log('Ballot Data:'.$ballotdata);

						#sign string.
						$signature = encryptWithUserKey($ballotdata);
						$check = ($ballotdata == decryptWithUserKey($signature)) ? 1 : 0;
						
						if ($check != 1)
						{
							$ret['measure_'.$measurecounter.'_SSLmsg']='Signature check failed!';
							
						}else{
							
							$ret['measure_'.$measurecounter.'_SSLmsg']='Signature check OK.';
						}

						
						$stmt = null;
						$stmt	= $pdo->prepare('CALL elections_cast_ballot(?,?,?,?,?,?,?)');		
						$stmt -> bindParam(1,$rId);					//registrant ID
						$stmt -> bindParam(2,$eId);					//election ID
						$stmt -> bindParam(3,$mId);					//measure ID
						$stmt -> bindParam(4,$selectedValue);		//option ID
						$stmt -> bindParam(5,$optionvalue);			//option value (for write-in values)
						$stmt -> bindParam(6,$provisional);			//provisional flag
						$stmt -> bindParam(7,$signature);			// signed data.
						$stmt -> execute();			
						$stmt -> closeCursor();
						$errs = $stmt->errorInfo();												
						if (empty($errs[1]))
						{
							if ($provisional > 0){
								$ret['measure_'.$measurecounter.'_message'] = 'Your ballot was cast outside the timeframe for the election and has been marked provisional.';
								$ret['provisional'] = 1;
							}else{
								$ret['measure_'.$measurecounter.'_message'] = 'Your ballot cast and recorded with secret hash to prevent tampering.';							
							}
						}else{							
							switch ($errs[1])
							{
								case "1062":
									$ret['measure_'.$measurecounter.'_message'] ='Ballot already cast.';
									$ret['code'] = '1062';
									$ret['message'] = 'You have already voted for this election.';
									break;
								default:
									$ret['code'] = $errs[1];
									$ret['measure_'.$measurecounter.'_message']= (print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
									break;
							}
							error_log($ret['measure_'.$measurecounter.'_message'].' RegistrantId='.$rId.', ElectionId='.$eId.', MeasureId='.$mId);
							$errors ++;
						}
						$measurecounter ++;
					}	// bottom of measure loop		
				}else{
					// election doesn't match, ignore.
				}
			}
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