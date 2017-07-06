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
	
	$results = array();
	$errors = array();
	
	$response['success'] = false;
	
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

					$results['electionStart']=$electionStartDate;
					$results['electionStop']=$electionEndDate;
					
					# loop through submitted measures, and commit ballot.
					foreach ($measures as $mId)
					{
						# future support for WRITE-IN process.
						$selectedValue = $_POST['measure-'.$mId]=='write-in' ? $_POST['measure-'.$mId.'-writein'] : $_POST['measure-'.$mId];
						$optionvalue = null;
						
						#error_log('Measure: '.$mId.'='.$selectedValue);
						
						#$response['SELECTED_mId'.$mId] = $selectedValue;
						$currentDateTime   = date('Y-m-d H:i:s');
						if ($currentDateTime > $electionStartDate && $currentDateTime < $electionEndDate)
						{
							$provisional = 0;
						}else{
							$provisional = 1;
						}
						$results['provisional'] = $provisional;
							
						# create ballot data string.
						$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue.'&TimeStamp='.$currentDateTime;
						#error_log('Ballot Data:'.$ballotdata);

						#sign string.
						## NOTE WELL
						## For implementation, you need to generate a private/public key pair on the server.
			
$private_key = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQGjjEYTVRC//K1eraJBAQahRKYfdTMeMFpgksDEeyEjLRbNeP+r
nYbIBQUdB/zkelo2sAg2gnxxcQHnq53I9xLgRYBYmzxEn9gicF1apbftgd743gd3
0NaZiYyedUvEQlvUU9f75Y9pB9ve1X/OdubKSUDUfZ5vxCxLdxjAK/EZ2wIDAQAB
AoGAQhqZ9/XRO86+KkrFY+SbfNFqM06uDTWlJ843nT2hTP+PtjQ4ZHvffqh708Us
bXtxt2gpAx2wKdeaazw5Ei8Lw+iBvDaKM/4pF3p9xDl8BC64xGx/veZtrqlgwZfV
EJ3a9turUfuEzpLO6Ajeverf1g4zM/Gry2Qwx9Ge+LZL0UECQQHUXndSPKahafWn
hO7QsOAYkWlrybwZjLH6H+iXqnLSdb4Clg1kCNTmfzMAFswOkCMVxd4APKaxpHM3
Bga46RIxAkEA5VDEqynmFFHFwp3YY5+6fUL4e4JY15P0kw8F4Qj8ekWqp+ApV0LL
rGhAvRhRQUXSPR4wO6QAtJ12gte2Meo9ywJAdjoORpz2tuIHV2zv14/+UVVLViEL
MSvZtTievsIQz91WxFJaOPkdvL05A3m9sqNhp9pVipvEvCy1xJrp0X1L0QJAHWKC
MSTHVOc9nji319xJ+28vhDQpykYtWwLb0ABkyg5PVf/FjGcEzmz1jsWk8+ZMkIRj
zeCwLnTXEOEvean4jQJBATpYdTd2opzu4u4idtYQpvghwFKXfL9WfMsw6gkezn/6
g3/8kHhtOW+WqrNKIIW6an+TjFIa8UnaJbFDzllqn0w=
-----END RSA PRIVATE KEY-----
EOD;

$public_key = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQGjjEYTVRC//K1eraJBAQahRKYf
dTMeMFpgksDEeyEjLRbNeP+rnYbIBQUdB/zkelo2sAg2gnxxcQHnq53I9xLgRYBY
mzxEn9gicF1apbftgd743gd30NaZiYyedUvEQlvUU9f75Y9pB9ve1X/OdubKSUDU
fZ5vxCxLdxjAK/EZ2wIDAQAB
-----END PUBLIC KEY-----
EOD;

						$signature = "";
						openssl_sign($ballotdata, $signature, $private_key, OPENSSL_ALGO_SHA1);
						$check = openssl_verify($ballotdata,$signature,$public_key, OPENSSL_ALGO_SHA1);
						
						## Future use: store a private/public key pair for each user. Encrypt their ballots with private key. Decrypt on vote.
						#openssl_free_key($private_key);

						$response['SSLsig']=$signature;						
						
						if ($check != 1)
						{
							$response['SSLmsg']='Signature check failed!';
						}else{
							$response['SSLmsg']='Signature check OK.';
						}

						#TODO - encrypt/sign ballot data.
						#$signature = $ballotdata;
						
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
							$results['ballot'] = 'Ballot cast.';							
						}else{							
							switch ($errs[1])
							{
								case "1062":
									$errors['ElectionId'] = $eId;
									$errors['MeasureId'] = $mId;
									$errors['RegistrantId'] = $rId;
									$errors['message'] ='Registrant has already cast ballot for this measure/election.';
									break;
								default:
									$errors['message']= (print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
									$errors['ElectionId'] = $eId;
									$errors['MeasureId'] = $mId;
									$errors['RegistrantId'] = $rId;
									break;
							}
						}
					}			
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
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 							}
			}
		}												
	}catch (PDOException $e)
	{
		echo($e->getMessage());
	}finally
	{
		if (empty($errors))
		{
			$response['success'] = true;				
			$response['message'] = $results;				
		}else
		{
			$response['success'] = false;
			$response['message'] = $errors;				
		}
		error_log($response['message']);
		error_log(print_r($errors));
		echo(json_encode($response));		
		$stmt = null;
		$pdo = null;			
		die();
	}		
?>