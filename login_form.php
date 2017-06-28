<?php
session_start();
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
	echo "Verb not supported.";	

}elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$errors = array();
	$ret = array();

	# if variable is set, get it's value, otherwise set to null.
	$uname = isset($_POST['usernameInput']) ? $_POST['usernameInput'] : null;
	if (empty($uname)){
		$errors['name'] = 'Username is blank';
	}else{
		$username = $_POST['usernameInput'];
	}
	$pwd   = isset($_POST['passwordInput']) ? $_POST['passwordInput'] : null;
	if (empty($pwd)){
		$errors['pwd'] = 'Password is blank';
	}else{
		$userpass_unhash = $_POST['passwordInput'];
	}		
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
			$sql	= 'CALL get_login(?)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1,$username);
			if ($stmt->execute()){
				$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				
				$userpass_hash = $row[0]['PWD_HASH'];
				$user_id = $row[0]['USER_ID'];
				if ($userpass_hash){
					if (hash_equals($userpass_hash, crypt($userpass_unhash, $userpass_hash))){
						$ret['success'] = true;
						$ret['message'] = 'User logged in.';
						$_SESSION['uid'] = $user_id;
						$_SESSION['username'] = $username;
						$_SESSION['pwd'] = $userpass_hash;
					}else{
						$ret['success'] = false;
						$ret['message'] = 'User NOT logged in (1).';
						$_SESSION = array();
						session_destroy();
					}				
				}
			}else{
				$ret['success'] = false;
				
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
					switch ($errs[1]){
						default:							
							$ret['message'] = 'User not logged in (2). Error '.$errs[1].': '.$errs[2];
					}
				}				
				$_SESSION = array();
				session_destroy();
			}									
		
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}
		finally{
			$stmt = null;
			$pdo = null;			
		}	
	}	
	# let's just clear this to be safe.
	$_SESSION['pwd']='';		
	echo json_encode($ret);
}else{
	echo "Verb not supported.";
}

?>