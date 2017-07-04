<?php 
	
	session_start();	
	
	require_once 'ac.php';	
	#require_once 'classes.php';
	
	$eid = isset($_POST['election']) ? $_POST['election'] : null;	
	$uid      = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
	$rid      = isset($_SESSION['rid']) ? $_SESSION['rid'] : null;
	$response = array();	
	$response['success'] = false;
	
	
	try {			
		$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		// we do this here to prevent session-spoofing; verifying that the logged-in user can actually access this election.
		$stmt	= $pdo->prepare('CALL registrant_get_elections(?)');		
		$stmt -> bindParam(1,$rid);				
		if ($stmt->execute()){
			// loop through user's elections...
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
				// we only get the election details for the selected election passed from POST.					
				if ($row['Election ID'] == $eid){	
					
					$election = array();
					$election['id'] = $row['Election ID'];
					$election['detail'] = htmlentities($row['Election Detail']);

					// get the measures for the selected election.		
					$stmt = null;				
					$stmt = $pdo->prepare('CALL elections_get_measures(?)');
					$stmt -> bindParam(1,$eid);						
					if ($stmt->execute()){												
						// loop through the election's measures...
						foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $rowa){	
							// Get the measure details.
							$measure = array();
							$measure['id'] = $rowa['Measure ID'];
							$measure['detail'] = htmlentities($rowa['Measure Detail']);
							
							// get the measure's options.
							$stmt = null;
							$stmt = $pdo->prepare('CALL elections_get_measure_options(?,?)');
							$stmt->bindParam(1,$eid);
							$stmt->bindParam(2,$rowa['Measure ID']);
							if ($stmt->execute()){										
								// loop through the measure's options.
								$options = array();
								foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $rowb){
									$options[] = ["id"=>$rowb['Option ID'],"detail"=>htmlentities($rowb['Option Detail'])];																	
								} // end of options loop.
								// add options to measure.
							}
							$measure['options'] = $options;														
							$measures[] = $measure;
							
						}// end of measure loop.
						$election['measures'] = $measures;						
						$elections[] = $election;
					}
					$response['success'] = true;
					$response['elections'] = $elections;					
				}// end of election selection.
			}// end of elections loop.
			$response['success'] = true;
			
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
	}finally{
		echo(json_encode($response));		
		$stmt = null;
		$pdo = null;			
		die();
	}		
?>