<?php
define('DSN','mysql:dbname=VOICE;host=localhost;');
define('DBUSER','VOICE');
define('DBPASS','1385362127Maya@123@');
define('DBPORT','3306');

$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));
function registrantList(){
	$errors = array();
	$aNull = null;
	$ret = array();
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call registrants_list()';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> execute();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt -> closeCursor();
				
				foreach ($users as $user)
				{		
					echo '<tr><td scope="row">'.$user['RID'].'</td>';
					echo '<td>'.$user['UID'].'</td>';
					echo '<td>'.$user['NAME'].'</td>';
					echo '<td>'.$user['Birth Date'].'</td>';
					echo '<td>'.$user['Phone'].'</td>';
					echo '<td>'.$user['State ID'].'</td>';
					echo '<td>'.$user['Fed ID'].'</td>';
					echo '<td>'.$user['Party'].'</td>';
					echo '<td>'.$user['State'].'</td>';
					switch ($user['Approval']){
						case "1":
							echo '<td><span class="label label-primary">Approved</span><br/><a href="#" onclick="rejectRegistrant('.$user['RID'].');"><span class="label label-default">Reject</span></a></td>';
							break;
						case "0":
							echo '<td><span class="label label-default">Approve</span><br/><a href="#" onclick="approveRegistrant('.$user['RID'].');"><span class="label label-default">Approve</span></a></td>';
							break;
						case "2":
							echo '<td><span class="label label-danger">Rejected</span><br/><a href="#" onclick="approveRegistrant('.$user['RID'].');"><span class="label label-default">Approve</span></a></td>';
							break;
					}
					if ($user['Affirm']=="1"){
						echo '<td><span class="label label-primary">Affirmed</span></td>';
					}else{
						echo '<td><span class="label label-danger">NOT AFFIRMED!</span></td>';						
					}
					//echo '<td>?</td>';
					echo '</tr>';
				}
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
	
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function registrantUnsetDistrict($reg,$dis){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}			
}
function registrantSetDistrict($reg,$dis){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function registrantSetApproved($reg){
	$errors = array();
	$ret = array();
	if (empty($reg))
	{
		$errors[] = 'Registrant ID cannot be empty.';
	}	
	$ret['function'] = 'registrantSetApproved';
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call registrant_set_approved(?)';
			$aNull = null;
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $reg); 
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$ret['message'] = 'Registrant approved.';
			}else{
				$ret['success'] = false;
				switch ($errs[1]){					
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}	
	echo json_encode($ret);
}
function registrantUnsetApproved($reg){
	$errors = array();
	$ret = array();
	if (empty($reg))
	{
		$errors[] = 'Registrant ID cannot be empty.';
	}	
	$ret['function'] = 'registrantUnsetApproved';
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call registrant_unset_approved(?)';
			$aNull = null;
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $reg); 
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$ret['message'] = 'Registrant unapproved.';
			}else{
				$ret['success'] = false;
				switch ($errs[1]){					
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}	
	echo json_encode($ret);
}
function registrantSetRejected($reg){
	$errors = array();
	$ret = array();
	if (empty($reg))
	{
		$errors[] = 'Registrant ID cannot be empty.';
	}	
	$ret['function'] = 'registrantSetRejected';
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call registrant_set_rejected(?)';
			$aNull = null;
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $reg); 
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$ret['message'] = 'Registrant rejected.';
			}else{
				$ret['success'] = false;
				switch ($errs[1]){					
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}	
	echo json_encode($ret);
}
function districtAdd($dist){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function districtDelete($dist){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function districtUpdate($dist,$distUpdate){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function userAddRole($usr,$role){
	// $errors holds error messages for this function.
	$errors = array();
	$ret = array();
	
	// precheck function parameters here. 	
	if (empty($role))
	{
		$errors[] = 'Role cannot be empty.';
	}	
	if (empty($usr)){
		$errors[] = 'User cannot be empty.';
	}
	// add failures to $errors.
	$ret['function'] = 'userAddRole';
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			#`` (IN userid bigint(20), IN roleid bigint (20), IN username varchar(256), IN role varchar(64))
			$sql	= 'call users_add_role2(?,?,?,?)';
			$aNull = null;
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $usr); 
			$stmt -> bindParam(2, $aNull); 
			$stmt -> bindParam(3, $aNull);
			$stmt -> bindParam(4, $role);
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				$ret['message'] = 'Role added to user.';
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					case '1062':
						$ret['message'] = 'User already has role.';
						break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}	
	echo json_encode($ret);
}
function userRevokeRole($usr,$role){
	$errors = array();
	$ret = array();
	if (empty($role))
	{
		$errors[] = 'Role cannot be empty.';
	}	
	if (empty($usr)){
		$errors[] = 'User cannot be empty.';
	}
	$ret['function'] = 'userRevokeRole';
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			$sql	= 'call users_delete_role(?,?,?,?)';
			$aNull = null;
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $usr); 
			$stmt -> bindParam(2, $aNull); 
			$stmt -> bindParam(3, $aNull);
			$stmt -> bindParam(4, $role);
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				$ret['success'] = true;
				$ret['message'] = 'Role revoked from user.';
			}else{
				$ret['success'] = false;
				switch ($errs[1]){					
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}	
	echo json_encode($ret);
}
function userList(){
	// $errors holds error messages for this function.
	$errors = array();
	$aNull = null;

	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. Add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call users_list()';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> execute();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				// loop through any result rows as necessary.
				// $results holds returned rows from database call (use if necessary).
				//$results = array();
				//while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					// add to array of results.
				//	$results[]=$row;
				//}

				//For-Each loop on result rows.				
				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt -> closeCursor();
				
				foreach ($users as $user)
				{		
					//
					echo '<tr><td scope="row">'.$user['USER_ID'].'</td><td>';
					// add admin role
					//echo '<a href="#" onclick="addUserRole('.$user['USER_ID'].',\'Administrators\');"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Add Admin Role</a><br/>';
					// add voter role
					//echo '<a href="#" onclick="addUserRole('.$user['USER_ID'].',\'Voters\');"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Add Voter Role</a><br/>';
					// delete action
					echo '<a href="#" onclick="deleteUser('.$user['USER_ID'].');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Delete</a>';
					
					echo '</td><td>'.$user['USER_NAME'].'</td><td>'.$user['EMAIL'].'</td>';
					
					// get user roles.
					$stmt = $pdo->prepare('call user_roles(?,?)');
					$stmt -> bindParam(1,$user['USER_ID']);
					$stmt -> bindParam(2,$aNull);
					$stmt -> execute();
					$errs = $stmt->errorInfo();					
					if (empty($errs[1])){
						$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$stmt -> closeCursor();
						$hasAdmin = false;
						$hasVoter = false;
						$adminString = '';
						$voterString = '';
						$defString = '';
						foreach($roles as $role){
							switch ($role['ROLE']){
								case 'Administrators':
									$hasAdmin = true;
									$adminString = '<a href="#" onclick="revokeUserRole('.$user['USER_ID'].',\'Administrators\');"><span class="label label-info">Admins</span></a>';
									break;
								case 'Voters':
									$hasVoter = true;
									$voterString = '<a href="#" onclick="revokeUserRole('.$user['USER_ID'].',\'Voters\');"><span class="label label-success">Voters</span></a>';
									break;
								default:
									$defString = '<a href="#" onclick="revokeUserRole('.$user['USER_ID'].',\'Voters\');"><span class="label label-primary">'.$role['ROLE'].'</span></a>';
							}
							
						}
						if (!$hasAdmin){
							$adminString = '<a href="#" onclick="addUserRole('.$user['USER_ID'].',\'Administrators\');"><span class="label label-default">Add Admin Role</span></a>';							
						}
						if (!$hasVoter){
							$voterString = '<a href="#" onclick="addUserRole('.$user['USER_ID'].',\'Voters\');"><span class="label label-default">Add Voter Role</span></a>';							
						}
						echo '<td>'.$adminString.'&nbsp;'.$voterString.'&nbsp;'.$defString.'</td>';
					}else{
						echo '<td>Unable to obtain roles.</td>';
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 	
					}
					echo '</tr>';
					
				}
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
	
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function electionsAdd(){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function electionsAddDistrict($el,$dist){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function electionsAddMeasure($el,$meas){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function electionsAddMeasureOption($el,$meas,$opt){
	// $errors holds error messages for this function.
	$errors = array();
	// $ret holds return values for this function.
	// Returns:
	//	message - applicable message(s)
	//	success - true|false success of operation. If false, error messages expected in 'message'.
	$ret = array();
	
	// precheck function parameters here. 	
	// add failures to $errors.
	
	if(!empty($errors)){
		$ret['message'] = $errors;
		$ret['success'] = false;
	}else{
		try {
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
			// set up stored procedure here. OUT params should be last, prefixed by @.
			$sql	= 'call SOMEFUNCTION(?,@newid)';
			$stmt	= $pdo->prepare($sql);						
			$stmt -> bindParam(1, $username); #, PDO::PARAM_STR,256); <- not necessary to use explicit typing, but here for reference.
			$stmt -> execute();
			$stmt -> closeCursor();
			$errs = $stmt->errorInfo();						
			if (empty($errs[1])) {
				// On success, perform any post-operation activity here, e.g. set message.
				$ret['success'] = true;
				
				// Use this to obtain OUT parameter values and do something with them.
				#$res = $pdo->query('SELECT @newid AS USER_ID')->fetch(PDO::FETCH_ASSOC);			
				#$ret['message'] = 'User created ' . $res['USER_ID'];
			}else{
				// On failure, perform any post-operation activity here, e.g. determine error(s).
				$ret['success'] = false;
				// Use this switch to capture database error conditions.
				switch ($errs[1]){
					//case "1062":
					//	$ret['message'] = 'Duplicate record exists.';
					//	break;
					default:
						$ret['message'] = 'There seems to be a problem. System administrators have been notified.';
						// Log error to PHP console. Could also return this message to caller, in case
						// it needs to be handled.
						error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						$ret['errors'] = 'Error '.$errs[1].': '.$errs[2];
				}
			}
		}catch (PDOException $e){
			// Catch-all error trap.
			$ret['success'] = false;
			$ret['message'] = $e->getMessage();				
		}	
	}		
}
function validateVote(){
	
	# take ballot data as recorded in system.
	# decrypt signature as recorded in system.	
	# compare options to recorded data - does it match?
	#$data = 'ElectionId='.$eId.'&UserId='.$uId.'&RegistrantId='.$rId.'&MeasureIds='.$_POST['measureIds'];
	#$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue;
	#$check = ($ballotdata == decryptWithUserKey($signature)) ? 1 : 0;				
	#$ballotdata = $data.'&MeasureId='.$mId.'&OptionId='.$selectedValue;
	# sig check				
	#$check = ($ballotdata == decryptWithUserKey($signature)) ? 1 : 0;
	return true;
}
function getUserKeys()
{
	$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
	$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
	$stmt -> bindParam(1,$_SESSION['uid']);			
	$stmt -> execute();
	$stmt -> closeCursor();
	$errs = $stmt->errorInfo();	
	if (empty($errs[1])) 
	{
		$res = $pdo->query('SELECT @publickey AS PUBLIC_KEY, @privatekey as PRIVATE_KEY')->fetch(PDO::FETCH_ASSOC);			
		
		$_SESSION['pubkey']  = isset($res['PUBLIC_KEY']) ? $res['PUBLIC_KEY'] : null; 
		$_SESSION['privkey'] = isset($res['PRIVATE_KEY']) ? $res['PRIVATE_KEY']: null;
		
	}else
	{
		$ret['success'] = false;
		switch ($errs[1])
		{
			default:
				$ret['message'] = 'Unable to add residence.';
				error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
		}
	}
}
function getUserPrivateKey()
{
	if (!isset($_SESSION['privkey']))
	{
		$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
		$stmt -> bindParam(1,$_SESSION['uid']);			
		$stmt -> execute();
		$stmt -> closeCursor();
		$errs = $stmt->errorInfo();	
		if (empty($errs[1])) 
		{
			$res = $pdo->query('SELECT @privatekey AS PRIVATE_KEY')->fetch(PDO::FETCH_ASSOC);				
			if (empty($res['PRIVATE_KEY'])){
				# key wasn't set, so, set it.
				createUserKeys();
				$pbK = $_SESSION['pubkey'];
				$pvK = $_SESSION['privkey'];
				try {
					#users_set_keys` (IN userid bigint(20), IN publickey longblob, IN privatekey longblob)
					$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
					$sql	= 'call users_set_keys(?,?,?)';
					$stmt	= $pdo->prepare($sql);						
					$stmt -> bindParam(1, $_SESSION['uid']);
					$stmt -> bindParam(2, $pbK);
					$stmt -> bindParam(3, $pvK);
					$stmt -> execute();
					$errs = $stmt->errorInfo();							
					if (!empty($errs[1])) {
						$ret['success'] = false;
						#uh-oh, report that something happens.
						switch ($errs[1]){
							case "1062":
								$ret['message'] = 'That username seems to be taken.';
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
			}	
		}else
		{
			$ret['success'] = false;
			switch ($errs[1])
			{
				default:
					$ret['message'] = 'Unable to retrieve private key.';
					error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
			}
		}
	}
	return $_SESSION['privkey'];
}
function getUserPublicKey()
{
	if (isset($_SESSION['pubkey']))
	{		
		$pdo = new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
		$stmt	= $pdo->prepare('CALL users_get_keys(?,@publickey, @privatekey)');		
		$stmt -> bindParam(1,$_SESSION['uid']);			
		$stmt -> execute();
		$stmt -> closeCursor();
		$errs = $stmt->errorInfo();	
		if (empty($errs[1])) 
		{
			$res = $pdo->query('SELECT @publickey AS PUBLIC_KEY')->fetch(PDO::FETCH_ASSOC);
			if (empty($res['PUBLIC_KEY'])){
				# key wasn't set, so, set it.
				createUserKeys();
				$pbK = $_SESSION['pubkey'];
				$pvK = $_SESSION['privkey'];
				try {
					#users_set_keys` (IN userid bigint(20), IN publickey longblob, IN privatekey longblob)
					$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));				
					$sql	= 'call users_set_keys(?,?,?)';
					$stmt	= $pdo->prepare($sql);						
					$stmt -> bindParam(1, $_SESSION['uid']);
					$stmt -> bindParam(2, $pbK);
					$stmt -> bindParam(3, $pvK);
					$stmt -> execute();
					$errs = $stmt->errorInfo();							
					if (!empty($errs[1])) {
						$ret['success'] = false;
						#uh-oh, report that something happens.
						switch ($errs[1]){
							case "1062":
								$ret['message'] = 'That username seems to be taken.';
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
				
			}					
		}else
		{
			$ret['success'] = false;
			switch ($errs[1])
			{
				default:
					$ret['message'] = 'Unable to retrieve public key.';
					error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
			}
		}
	}
	return $_SESSION['pubkey'];
}
function decryptWithUserKey($dataToDecrypt)
{
	openssl_private_decrypt($dataToDecrypt, $decrypted, getUserPrivateKey());
	return $decrypted;
}
function encryptWithUserKey($dataToEncrypt)
{
	openssl_public_encrypt($dataToEncrypt, $encrypted, getUserPublicKey());
	return $encrypted;
}


function createUserKeys() {
		## Generate Private and Public key for user.

	$keyconfig = array("digest_alg" => "sha512","private_key_bits" => 4096,"private_key_type" => OPENSSL_KEYTYPE_RSA,);
	$res = openssl_pkey_new($keyconfig);
	openssl_pkey_export($res, $privKey);
	$pubKey = openssl_pkey_get_details($res);
	$pubKey = $pubKey["key"];

	$_SESSION['pubkey'] = $pubKey;
	$_SESSION['privkey']= $privKey;
	
}

	
	
?>