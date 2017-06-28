<?php
	session_start();
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
# use this for testing.
/*
		$username = 'Test2';
		$userpass = 'Password123';
		$useremail = 'test@123.com';
		$hashcost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),'+','.');
		$alg = '$2a$%02d$';
		$salt = sprintf($alg,$hashcost) . $salt;
		$userpass = crypt($userpass,$salt);

		try {			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS);				
			$sql	= 'call users_add(?,?,?,?,?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username, PDO::PARAM_STR,256);
			$stmt -> bindParam(2, $useremail, PDO::PARAM_STR,256);
			$stmt -> bindParam(3, $userpass, PDO::PARAM_STR,256);
			$stmt -> bindParam(4, $alg, PDO::PARAM_STR,50);
			$stmt -> bindParam(5, $salt, PDO::PARAM_STR,50);
			$stmt -> execute();
			$stmt -> closeCursor();

			$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
			echo "<hr/>NewID Results:\n";
			foreach ($res as $key => $val) {
			    print "$key = $val\n";
			}
			
		} catch (PDOException $e){
			echo "PDO Exception: " . $e->getMessage();				
		}	
		*/
	echo "Verb not supported.";	
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$errors = array();
	$ret = array();

	# if variable is set, get it's value, otherwise set to null.
	$uname = isset($_POST['usernameInput']) ? $_POST['usernameInput'] : null;
	if (empty($uname)){
		$errors['name'] = 'Username is blank';
	}else{
		$username = $_POST['usernameInput'];
	}
	
	$email = isset($_POST['emailInput']) ? $_POST['emailInput'] : null;
	if (empty($email)){
		$errors['email'] = 'Email is blank';
	}else{
		$useremail = $_POST['emailInput'];
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
		$hashcost = 10;
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),'+','.');
		$alg = "$2a$%02d$";
		$salt = sprintf($alg,$hashcost) . $salt;
		$userpass = crypt($userpass_unhash,$salt);

		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call users_add(?,?,?,?,?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username, PDO::PARAM_STR,256);
			$stmt -> bindParam(2, $useremail, PDO::PARAM_STR,256);
			$stmt -> bindParam(3, $userpass, PDO::PARAM_STR,256);
			$stmt -> bindParam(4, $alg, PDO::PARAM_STR,50);
			$stmt -> bindParam(5, $salt, PDO::PARAM_STR,50);
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();
						
			if (empty($errs[1])) {
				# no errors, get the user ID.
				$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				$ret['success'] = true;
				$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				$ret['success'] = false;
				#uh-oh, report that something happens.
				switch ($errs[1]){
					case "1062":
						$ret['message'] = 'That username seems to be taken.';
						break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}			
	echo json_encode($ret);
}else{
	echo "Verb not supported.";
}

?>