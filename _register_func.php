<?php

		try {
			
			$pdo 	= new PDO(DSN,DBUSER,DBPASS,array(PDO::ATTR_PERSISTENT => true));			
			#country parameter is HARD CODED for this implementation.
			$queryState = 'USA';
			$stmt	= $pdo->prepare('CALL state_list(?)');
			$stmt -> bindParam(1,$queryState);
			$states = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$states[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
					switch ($errs[1]){
						default:
							error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
					}
				}
			}
			
			$stmt	= $pdo->prepare('CALL ethnicities_list()');						
			$eths = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$eths[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
						switch ($errs[1]){
							default:
								error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
						}
				}
			}
			
			$stmt	= $pdo->prepare('CALL genders_list()');						
			$genders = array();
			if ($stmt->execute()){
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$genders[]=$row;
				}
			}else{
				$errs = $stmt->errorInfo();	
				if (!empty($errs[1])) {						
					switch ($errs[1]){
						default:
							error_log(print_r('Error '.$errs[1].': '.$errs[2], TRUE)); 								
					}
				}
			}
			
			
		}catch (PDOException $e){			
			echo($e->getMessage());
			die();
		}
		finally{
			$stmt = null;
			$pdo = null;			
		}
		?>