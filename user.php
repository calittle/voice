<?php
    
    # this is just temporary until we start doing stuff in PHP.
    ob_start();
    header('Location: registrant.html');
    ob_end_flush();
    die();
    
    
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		#$Host			= "localhost"
		#$DatabaseName 	= "VOICE"
		#$Username 		= "VOICE"
		#$Password		= "1385362127Maya@123@"
		 
			
		#  $DB = new mysqli($Host, $Username, $Password, $DatabaseName);
		#   if (mysqli_connect_errno())
		#       trigger_error("Unable to connect to MySQLi database.");
		#   $DB->set_charset('utf8');
	
	}
?>