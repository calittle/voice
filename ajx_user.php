<?php 
	session_start();	

	require_once 'config.php';
	$errors = array();
	$msgs = array();
	$ret = array();
	
	if($_SERVER['REQUEST_METHOD'] != 'POST') 
	{
		$errors['Verb'] = 'Only POST is supported.';
	}
	
	$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
	if (empty($uid)){
		$errors['uid'] = 'User ID is blank';
	}
	
	$eml   				= isset($_POST['emailInput']) ? $_POST['emailInput'] : null;
	$userpass_unhash	= isset($_POST['passwordInput']) ? $_POST['passwordInput'] : null;

	if (empty($userpass_unhash) & empty($eml)){
		$errors['command'] = 'Password and email are blank; nothing to do!';
	}	
	if(!empty($errors)){
	
		$ret['message'] = $errors;
		$ret['success'] = false;
		
	}else{
		if (!empty($eml))
		{
			try 
			{
				$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
				$sql	= 'call users_update_email(?,?)';
				$stmt	= $pdo->prepare($sql);						
				$stmt -> bindParam(1, $uid);
				$stmt -> bindParam(2, $eml);
				$stmt -> execute();
				$stmt -> closeCursor();
				$errs = $stmt->errorInfo();
							
				if (empty($errs[1])) {
					$ret['success'] = true;
					$msgs['email'] = 'User email updated';				
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
				$msgs['email'] = $e->getMessage();				
				
			}						
		}
		if (!empty($userpass_unhash))
		{
			$hashcost = 10;
			$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),'+','.');
			$alg = "$2a$%02d$";
			$salt = sprintf($alg,$hashcost) . $salt;
			$userpass = crypt($userpass_unhash,$salt);
	
			try {
				$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
				$sql	= 'call users_update_password(?,?,?,?)';
				$stmt	= $pdo->prepare($sql);						
				$stmt -> bindParam(1, $uid);
				$stmt -> bindParam(2, $userpass);
				$stmt -> bindParam(3, $alg);
				$stmt -> bindParam(4, $salt);
				$stmt -> execute();
				$stmt -> closeCursor();
				$errs = $stmt->errorInfo();
							
				if (empty($errs[1])) {
					$ret['success'] = true;
					$msgs['password'] = 'User password updated';				
				}else{
					$ret['success'] = false;
					#uh-oh, report that something happens.
					switch ($errs[1]){
						default:
							$msgs['password'] = 'Error '.$errs[1].': '.$errs[2];
							error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
					}
				}
			}catch (PDOException $e){
				$ret['success'] = false;
				$msgs['password'] = $e->getMessage();				
			}	
		}
		$ret['messages'] = $msgs;
	}				
	echo json_encode($ret);
	die();
?>