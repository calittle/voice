<?php 
	session_start();	

	require_once 'config.php';
	
	$state = $_SESSION['regdata']['stateInput'];
	$parties = array();
	
	try {			
		$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL state_parties_list(?)');
		$stmt -> bindParam(1,$state);		
		if ($stmt->execute()){
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$parties[]=$row;
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
	}finally{
		echo(json_encode($parties));		
		$stmt = null;
		$pdo = null;			
		die();
	}		
?>