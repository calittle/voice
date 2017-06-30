<?php
session_start();
include 'ac.php'; 
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$_SESSION['regdata'] = isset($_SESSION['regdata']) ? array_merge($_SESSION['regdata'],$_POST) : $_POST;
	$regdata = $_SESSION['regdata'];
	# add location.
	try {
			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			

			$stmt	= $pdo->prepare('CALL locations_add(?,?,?,?,?,?,?,?,?,?,@newid)');

			$a = $regdata['street1Input'];
			$stmt -> bindParam(1,$a,PDO::PARAM_STR,256);			

			$b=$regdata['street2Input'];
			$stmt -> bindParam(2,$b,PDO::PARAM_STR,256);						

			$c=$regdata['cityInput'];
			$stmt -> bindParam(3,$c,PDO::PARAM_STR,256);

			$d=$regdata['postCodeInput'];
			$stmt -> bindParam(4,$d,PDO::PARAM_STR,256);

			$e=$regdata['countyInput'];
			$stmt -> bindParam(5,$e,PDO::PARAM_STR,64);			

			$f=$regdata['stateResidenceInput'];
			$stmt -> bindParam(6,$f,PDO::PARAM_STR,2);

			$g=$regdata['countryInput'];
			$stmt -> bindParam(7,$g,PDO::PARAM_STR,3);

			$h=$_SESSION['rid'];
			$stmt -> bindParam(8,$h,PDO::PARAM_INT);								

			$i=$regdata['isResidenceInput'];
			$stmt -> bindParam(9,$i,PDO::PARAM_BOOL);

			$j=$regdata['isMailingInput'];
			$stmt -> bindParam(10,$j,PDO::PARAM_BOOL);

			$stmt -> execute();
			
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();		
							
			if (empty($errs[1])) {
				# no errors, get the user ID.
				$res = $pdo->query('SELECT @newid AS REG_ID')->fetch(PDO::FETCH_ASSOC);			
				$ret['success'] = true;
				$ret['message'] = 'Location added ' . $res['REG_ID'];
				$_SESSION['lid'] = $res['REG_ID'];				
			}else{
				$ret['success'] = false;
				switch ($errs[1]){
					default:
						$ret['message'] = 'Unable to add residence.';
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

