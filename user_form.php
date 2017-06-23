<?php
$return = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
#  $return['success'] = True;
#  $return['message'] = 'Login successful!';
#} else {
  $return['success'] = False;
  $return['message'] = 'Bad user name / password';
}
print json_encode($return);
/*	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		$errors = array();
		$ret = array();

		# if variable is set, get it's value, otherwise set to null.
		$uname = isset($_POST['usernameInput']) ? $_POST['usernameInput'] : null;
		if (empty($uname)){
			$errors['name'] = 'Username is blank';
		}
		$email = isset($_POST['emailInput']) ? $_POST['emailInput'] : null;
		if (empty($email)){
			$errors['email'] = 'Email is blank';
		}
		$pwd   = isset($_POST['passwordInput']) ? $_POST['passwordInput'] : null;
		if (empty($pwd)){
			$errors['pwd'] = 'Password is blank';
		}
		if(!empty($errors)){
			$ret['errors'] = $errors;
			$ret['success'] = false;
		}else{
			$ret['success'] = true;			
		}
		
		$ret['success'] = true;
		$ret['posted'] = 'Data Was Posted Successfully';
		
		echo json_encode($ret);
		#$Host			= "localhost"
		#$DatabaseName 	= "VOICE"
		#$Username 		= "VOICE"
		#$Password		= "1385362127Maya@123@"
		 
			
		#  $DB = new mysqli($Host, $Username, $Password, $DatabaseName);
		#   if (mysqli_connect_errno())
		#       trigger_error("Unable to connect to MySQLi database.");
		#   $DB->set_charset('utf8');
	
	}
	*/
?>