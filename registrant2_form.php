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

			# Note: due to quirk of bindParam, it doesn't bind the value until query is executed, so can't reuse same variable.
			$a=$regdata['firstNameInput'];
			$stmt -> bindParam(1,$a);
			$b=$regdata['middleNameInput'];
			$stmt -> bindParam(2,$b);						
			$c=$regdata['lastNameInput'];
			$stmt -> bindParam(3,$c);
			$d=$regdata['suffixNameInput'];
			$stmt -> bindParam(4,$d);
			$e=$regdata['birthDateInput'];
			$stmt -> bindParam(5,$e);			
			$f=$regdata['phoneInput'];
			$stmt -> bindParam(6,$f);
			$g=$regdata['stateIdInput'];
			$stmt -> bindParam(7,$g);						
			$h=$regdata['fedIdInput'];
			$stmt -> bindParam(8,$h);

			# these can possess null values, but not empty string (will fail constraints).
			# use ternary operator to populate with null.
			$i=empty($regdata['genderInput'])? null:$regdata['genderInput'];
			$stmt -> bindParam(9,$i);
			$j=empty($regdata['ethnicityInput'])?null:$regdata['ethnicityInput'];
			$stmt -> bindParam(10,$j);			
			$k=empty($regdata['affirmInput'])?null:$regdata['affirmInput'];
			$stmt -> bindParam(11,$k);
			$l=$regdata['stateInput'];
			$stmt -> bindParam(12,$l);						
			$m=$regdata['partyInput'];
			$stmt -> bindParam(13,$m);
			$stmt -> bindParam(14,$uid);						
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				# no errors, get the user ID.
				$res = $pdo->query('SELECT @newid AS REG_ID')->fetch(PDO::FETCH_ASSOC);			
				$ret['success'] = true;
				$ret['message'] = 'Registrant created ' . $res['REG_ID'];
				$_SESSION['rid'] = $res['REG_ID'];
				$_SESSION['statecd'] = $l;
				
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

