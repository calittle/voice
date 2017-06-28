<?php
	session_start();
	include 'ac.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

	$_SESSION['regdata'] = $_POST;
/*
    # store entered data to session.
    # FN, MN, LN, SFX, TEL, BD, SID, FID, ST, ETH, GEN
    $regdata = array();
	$regdata['fn']  = isset($_POST['firstNameInput']) ? $_POST['firstNameInput'] : null;
    $regdata['mn']  = isset($_POST['middleNameInput']) ? $_POST['middleNameInput'] : null; 
    $regdata['ln']  = isset($_POST['lastNameInput']) ? $_POST['lastNameInput'] : null;         
    $regdata['sfx'] = isset($_POST['suffixNameInput']) ? $_POST['suffixNameInput'] : null; 
    $regdata['tel'] = isset($_POST['phoneInput']) ? $_POST['phoneInput'] : null; 
    $regdata['bd']  = isset($_POST['birthDateInput']) ? $_POST['birthDateInput'] : null; 
    $regdata['sid'] = isset($_POST['stateIdInput']) ? $_POST['stateIdInput'] : null; 
    $regdata['fid'] = isset($_POST['fedIdInput']) ? $_POST['fedIdInput'] : null; 
    $regdata['st']  = isset($_POST['stateInput']) ? $_POST['stateInput'] : null; 
    $regdata['eth'] = isset($_POST['ethnicityInput']) ? $_POST['ethnicityInput'] : null; 
    $regdata['gen'] = isset($_POST['lastNameInput']) ? $_POST['genderInput'] : null; 
 */   
 
 
 
    # move to next page.
    ob_start();
    header('Location: registrant2.php');
    ob_end_flush();
    die();
} else {
	echo "Verb not supported.";	
}
?>