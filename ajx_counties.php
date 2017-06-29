<?php 
	session_start();	

	require_once 'config.php';	

	if (isset($_POST['stateResidenceInput'])){
		$state = $_POST['stateResidenceInput'];		
	}else{
		$state = $_SESSION['regdata']['stateInput'];		
	}
	$counties = array();
	
	try {			
		$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL counties_list(?)');
		$stmt -> bindParam(1,$state);		
		if ($stmt->execute()){
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$counties[]=$row;
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
		echo(json_encode($counties));		
		$stmt = null;
		$pdo = null;			
		die();
	}		
?>