<?php 

#session_start();

include_once 'config.php';

$uid = isset($_POST['uid']) ? $_POST['uid'] : isset($_SESSION['uid'])?$_SESSION['uid']:null;
# Note: this the hash, not the actual password.
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : isset($_SESSION['pwd'])?$_SESSION['pwd']:null;
	
if (!isset($uid)){
	header("Location: login.php?y=".$thispage.".php");
	ob_end_flush();
	exit;
}
function validateVote(){
	
	# take ballot data as recorded in system.
	# decrypt signature as recorded in system.	
	# compare options to recorded data - does it match?
	#$data = 'ElectionId='.$eId.'&UserId='.$uId.'&RegistrantId='.$rId.'&MeasureIds='.$_POST['measureIds'];
	#$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue;
	#$check = ($ballotdata == decryptWithUserKey($signature)) ? 1 : 0;				
	#$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue;
	# sig check				
	#$check = ($ballotdata == decryptWithUserKey($signature)) ? 1 : 0;
	return true;
}
function getUserKeys()
{
	$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
	$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
	$stmt -> bindParam(1,$_SESSION['uid']);			
	$stmt -> execute();
	$stmt -> closeCursor();
	$errs = $stmt->errorInfo();	
	if (empty($errs[1])) 
	{
		$res = $pdo->query('SELECT @publickey AS PUBLIC_KEY, @privatekey as PRIVATE_KEY')->fetch(PDO::FETCH_ASSOC);			
		
		$_SESSION['pubkey']  = isset($res['PUBLIC_KEY']) ? $res['PUBLIC_KEY'] : null; 
		$_SESSION['privkey'] = isset($res['PRIVATE_KEY']) ? $res['PRIVATE_KEY']: null;
		
	}else
	{
		$ret['success'] = false;
		switch ($errs[1])
		{
			default:
				$ret['message'] = 'Unable to add residence.';
				error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
		}
	}
}
function getUserPrivateKey()
{
	if (!isset($_SESSION['privkey']))
	{
		$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
		$stmt -> bindParam(1,$_SESSION['uid']);			
		$stmt -> execute();
		$stmt -> closeCursor();
		$errs = $stmt->errorInfo();	
		if (empty($errs[1])) 
		{
			$res = $pdo->query('SELECT @privatekey AS PRIVATE_KEY')->fetch(PDO::FETCH_ASSOC);				
			if (empty($res['PRIVATE_KEY'])){
				# key wasn't set, so, set it.
				createUserKeys();
				$pbK = $_SESSION['pubkey'];
				$pvK = $_SESSION['privkey'];
				try {
					#users_set_keys` (IN userid bigint(20), IN publickey longblob, IN privatekey longblob)
					$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
					$sql	= 'call users_set_keys(?,?,?)';
					$stmt	= $pdo->prepare($sql);						
					$stmt -> bindParam(1, $_SESSION['uid']);
					$stmt -> bindParam(2, $pbK);
					$stmt -> bindParam(3, $pvK);
					$stmt -> execute();
					$errs = $stmt->errorInfo();							
					if (!empty($errs[1])) {
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
		}else
		{
			$ret['success'] = false;
			switch ($errs[1])
			{
				default:
					$ret['message'] = 'Unable to retrieve private key.';
					error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
			}
		}
	}
	return $_SESSION['privkey'];
}
function getUserPublicKey()
{
	if (isset($_SESSION['pubkey']))
	{		
		$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
		$stmt -> bindParam(1,$_SESSION['uid']);			
		$stmt -> execute();
		$stmt -> closeCursor();
		$errs = $stmt->errorInfo();	
		if (empty($errs[1])) 
		{
			$res = $pdo->query('SELECT @publickey AS PUBLIC_KEY')->fetch(PDO::FETCH_ASSOC);
			if (empty($res['PUBLIC_KEY'])){
				# key wasn't set, so, set it.
				createUserKeys();
				$pbK = $_SESSION['pubkey'];
				$pvK = $_SESSION['privkey'];
				try {
					#users_set_keys` (IN userid bigint(20), IN publickey longblob, IN privatekey longblob)
					$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
					$sql	= 'call users_set_keys(?,?,?)';
					$stmt	= $pdo->prepare($sql);						
					$stmt -> bindParam(1, $_SESSION['uid']);
					$stmt -> bindParam(2, $pbK);
					$stmt -> bindParam(3, $pvK);
					$stmt -> execute();
					$errs = $stmt->errorInfo();							
					if (!empty($errs[1])) {
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
		}else
		{
			$ret['success'] = false;
			switch ($errs[1])
			{
				default:
					$ret['message'] = 'Unable to retrieve public key.';
					error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
			}
		}
	}
	return $_SESSION['pubkey'];
}
function decryptWithUserKey($dataToDecrypt)
{
	openssl_private_decrypt($dataToDecrypt, $decrypted, getUserPrivateKey());
	return $decrypted;
}
function encryptWithUserKey($dataToEncrypt)
{
	openssl_public_encrypt($dataToEncrypt, $encrypted, getUserPublicKey());
	return $encrypted;
}


function createUserKeys() {
		## Generate Private and Public key for user.

	$keyconfig = array("digest_alg" => "sha512","private_key_bits" => 4096,"private_key_type" => OPENSSL_KEYTYPE_RSA,);
	$res = openssl_pkey_new($keyconfig);
	openssl_pkey_export($res, $privKey);
	$pubKey = openssl_pkey_get_details($res);
	$pubKey = $pubKey["key"];

	$_SESSION['pubkey'] = $pubKey;
	$_SESSION['privkey']= $privKey;
	
}

# if we are still here...
?>