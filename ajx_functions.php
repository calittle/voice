<?php 
	session_start();	

	require_once 'config.php';	

	// generic handler for PHP functions to be called on post.
	$par1 = isset($_POST['par1']) ? $_POST['par1'] : null;
	$par2 = isset($_POST['par2']) ? $_POST['par2'] : null;
	$par3 = isset($_POST['par3']) ? $_POST['par3'] : null;
	$par4 = isset($_POST['par4']) ? $_POST['par4'] : null;	
	$function  = isset($_POST['function']) ? $_POST['function'] : null;
	$results = array();
	
	
	switch ($function){
		case 'addrole':
			$results = userAddRole($par1,$par2);
			break;
		case 'revokerole':
			$results = userRevokeRole($par1,$par2);
			break;
		case 'approveregistrant':
			$results = registrantSetApproved($par1);
			break;
		case 'rejectregistrant':
			$results = registrantSetRejected($par1);
			break;
		case 'districtadd':
			break;
		case 'districtdelete':
			break;
		case 'districtupdate':
			break;
		case 'setaffirmations':
			$results = registrantSetAffirmations($par1);			
		default:
			break;
	}
	echo $results;
?>