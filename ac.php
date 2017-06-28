<?php 

session_start();

include_once 'config.php';

$uid = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['uid'];
# Note: this the hash, not the actual password.
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];
	
if (!isset($uid)){
	header("Location: login.php");
	ob_end_flush();
	exit;
}
# if we are still here...
?>