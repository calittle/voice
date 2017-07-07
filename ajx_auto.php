<?php 
	session_start();	

	require_once 'config.php';
	$errors = array();
	$msgs = array();
	$ret = array();
	
	$rid = isset($_SESSION['rid']) ? $_SESSION['rid'] : null;
	if (empty($rid)){
		$errors['rid'] = 'Registrant ID is blank';
	}
	if(!empty($errors)){
	
		$ret['message'] = $errors;
		$ret['success'] = false;
		
	}else{
		try 
		{
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'CALL registrant_set_approved(?)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $rid);
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();
						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$ret['message'] = 'User approved.';				
			}else{
				$ret['success'] = false;
				#uh-oh, report that something happens.
				switch ($errs[1]){
					default:
						$msgs['email'] = 'Error '.$errs[1].': '.$errs[2];
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();								
		}								
	}
	echo json_encode($ret);
	die();
?>