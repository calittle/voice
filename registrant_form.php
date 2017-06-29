<?php
	session_start();
	include 'ac.php'; 
	$ret = array();
	$ret['success'] = false;
	try{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			$_SESSION['regdata'] = $_POST;
			$ret['success'] = true;
			$ret['message'] = 'Registrant data added.';
		}else{
			$ret['message'] = 'Verb not supported';
		}
	}
	catch(Exception $e){
		$ret['message'] = $e->getMessage();						
	}
	finally{
		echo json_encode($ret);
	}
?>