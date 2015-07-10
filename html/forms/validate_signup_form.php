<?php 
if (session_status() == PHP_SESSION_NONE) {
	session_start();
	error_reporting(E_ALL);
}

  	// Grab the values for check_database,
  	$name = htmlspecialchars($_POST['username']);
  	$email = htmlspecialchars($_POST['email']);
  	$pass = htmlspecialchars($_POST['first_password']);
	  	
	  	
	  	
	if($name != ''){
		
	
		if($email != ''){
			
		
			if($pass != '') {
				
				
	  	$sql = "SELECT COUNT(*) FROM ".TABLE_USER;
	  	if ($res = $dbh->query($sql)) {
	  		/* Check the number of rows that match the SELECT statement,
	  		   If no rows exist then we can assume no one has ever been added
	  		   To this database. */
	  		if ($res->fetchColumn() > 0) {
	  			
	  			// this is where the beginTransaction() was called.
	  			
	  			foreach($dbh -> query("SELECT `user_id`, `username`, `password`, `email`, `available_funds` FROM ".TABLE_USER) as $row){
	  				 
	  			
	  				// if someone has that username
	  				if($row['username'] == $name){
	  			
	  					//echo "Someone already has that username!";
			header("Location: ../index.php?error=username");
	  						  					die();
	  				}
	  			
	  				// if someone has taken that email address
	  				else if($email == $row['email']){
	  			
	  					//echo "Someone already has that email address!";
			header("Location: ../index.php?error=email");
	  						  					die();
	  				}
	  				// if we have a valid
	  				else {
	  					$allGood = true;
	  					// we will redirect the user to the login page if we can
	  			
	  					// at first i am attempting to add this user to the account
	  					
	  			
	  				}
	  			
	  			}
	  				$dbh->commit();
	  			if(isset($allGood)){
	  				$dbh->beginTransaction();
	  				$dbh -> query("INSERT INTO ".DATABASE.".".TABLE_USER." (`user_id`, `username`, `password`, `email`, `available_funds`) VALUES (NULL, '$name', '".crypt($pass,st)."', '$email', '10000')");
	  					  				// this is the only spot where we set the SESSION
	  				$_SESSION['logged'] = true;
	  				$_SESSION['username'] = $name;
	  				$_SESSION['email'] = $email;
	  				$_SESSION['password'] = $pass;
	  				$_SESSION['available_funds'] = 10000.00;
	  				$_SESSION['available_fund'] = 10000.00;
	  				$_SESSION['user_id'] = $dbh->lastInsertId();
	  				$dbh->commit();
	  				header("Location: ../home.php");
	  				
	  				//echo "all is good";
	  			}
	  		
	  				  		
	  		}
	  		/* No Entries already in the Database, so just add the name */
	  		else {
		  		$dbh->commit();
	  			$dbh->beginTransaction();
	  			$dbh -> query("INSERT INTO ".DATABASE.".".TABLE_USER." (`user_id`, `username`, `password`, `email`, `available_funds`) VALUES (NULL, '$name', '".crypt($pass,st)."', '$email', '10000')");
	  			// this is the only spot where we set the SESSION
	  			$_SESSION['logged'] = true;
	  			$_SESSION['username'] = $name;
	  			$_SESSION['email'] = $email;
	  			$_SESSION['password'] = $pass;
	  			$_SESSION['available_fund'] = 10000.00;
	  			$_SESSION['available_funds'] = 10000.00;
	  			$_SESSION['user_id'] = $dbh->lastInsertId();
	  			$dbh->commit();
	  			header("Location: ../home.php");
	  		}
	  	}
	  	
		}else {
			header("Location: ../index.php?error=username");
		}
	}else {
			header("Location: ../index.php?error=username");
			}
}else {
			header("Location: ../index.php?error=username");
	}
	  	
	 
	  	
	  	
	  


?>