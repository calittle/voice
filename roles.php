<?php 

#session_start();

include_once 'config.php';

# Does the logged-in user have access to this page?
$userid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

$username = null;

try
{			
	$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			

	switch ($thispage){
		case 'admin': #requires Administrator role
			error_log(' Accessing ADMIN page');
			$stmt	= $pdo->prepare('CALL user_has_admin(?,?)');
			break;
		case 'vote': #requires Voter role
			error_log(' Accessing VOTER page');
			$stmt	= $pdo->prepare('CALL state_has_voter(?,?)');
			break;
		default:
			$stmt = null;
	}
	if ($stmt != null and $userid != null) 
	{
		# role-protected page and user is logged in
		$stmt -> bindParam(1,$userid);
		$stmt -> bindParam(2,$username);		
		if ($stmt->execute()){
			$row = $stmt->fetchColumn();
			if ($row==0){
				error_log(print_r($row));
				error_log("Forbidden page attempt");
				header('Location: index.php?x=403');
			}

		}else{
			$errs = $stmt->errorInfo();	
			if (!empty($errs[1])) {						
				switch ($errs[1]){
					# add other cases here.
					default:
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
				}
			}
		}
	}elseif ($stmt != null and $userid == null){
		# role-protected page and user is not logged in.
		header('Location: index.php?x=403');
	}elseif ($stmt == null){
		# not role protected, not logged in...
		# do nothing
	}	
}catch (PDOException $e)
{			
	echo($e->getMessage());
}finally
{
	$stmt = null;
	$pdo = null;		
}
	
?>