<?php
session_start();
include 'ac.php'; 
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$_SESSION['regdata'] = array_merge($_SESSION['regdata'],$_POST);
	$regdata = $_SESSION['regdata'];
	
	# add registrant.
	try {
			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			

			$stmt	= $pdo->prepare('CALL registrant_add(?,?,?,?,?,?,?,?,?,?,?,?,?,?,@newid)');
			# FN,MN,LN,SUF (256,BD,TEL64,SID256,FID32,GEN16,ETH2,AFFtinyint1,STATE3,PARTY16,USERID
			$stmt -> bindParam(1,$a=$regdata['firstNameInput']);
			$stmt -> bindParam(2,$a=$regdata['middleNameInput']);						
			$stmt -> bindParam(3,$a=$regdata['lastNameInput']);
			$stmt -> bindParam(4,$a=$regdata['suffixNameInput']);
			$stmt -> bindParam(5,$a=$regdata['birthDateInput']);			
			$stmt -> bindParam(6,$a=$regdata['phoneInput']);
			$stmt -> bindParam(7,$a=$regdata['stateIdInput']);						
			$stmt -> bindParam(8,$a=$regdata['fedIdInput']);
			$stmt -> bindParam(9,$a=$regdata['genderInput']);
			$stmt -> bindParam(10,$a=$regdata['ethnicityInput']);			
			$stmt -> bindParam(11,$a=$regdata['affirmInput']);
			$stmt -> bindParam(12,$a=$regdata['stateInput']);						
			$stmt -> bindParam(13,$a=$regdata['partyInput']);
			$stmt -> bindParam(14,$a=$uid);						
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				# no errors, get the user ID.
				$res = $pdo->query('SELECT @newid AS REG_ID')->fetch(PDO::FETCH_ASSOC);			
				$ret['success'] = true;
				$ret['message'] = 'Registrant created ' . $res['REG_ID'];
				$_SESSION['rid'] = $res['REG_ID'];
				
			}else{
				$ret['success'] = false;
				switch ($errs[1]){
					case "1062":
						$ret['message'] = 'There is already a registrant on your user account.';
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
		finally{
			$stmt = null;
			$pdo = null;			
		}	
		echo json_encode($ret);
} else {
	echo "Verb not supported.";	
}
?>

