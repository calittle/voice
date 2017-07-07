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

# if we are still here...
?>