<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    error_reporting(E_ALL);
}



// connect to the database
//$dsn = 'mysql:host=localhost;dbname=database_stocks';
// create a new PDO
//$dbh = new PDO($dsn, 'root', '');

$name = htmlspecialchars($_POST['username']);
$pass = htmlspecialchars($_POST['first_password']);

$weGood = false;
//$dbh->beginTransaction();

foreach($dbh->query('SELECT `user_id`, `username`, `password`, `email`, `available_funds` FROM '.TABLE_USER)as $row){
	if(($name == $row['username'] && crypt($pass,'st') == $row['password']) || ($row['email'] == $name && $pass == $row['password'])){
		$_SESSION['logged'] = true;
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['username'] = $row['username'];
		//$_SESSION['password'] = $row['password'];
		$_SESSION['available_funds'] = $row['available_funds'];
		$_SESSION['email'] = $row['email'];
		$weGood= true;
		//echo "got a match";
		header("Location: ../home.php");
	}
}
$dbh->commit();
if($weGood == false){
	if($name != $row['username'] || $row['email'] == $name){
		header("Location: ../index.php?error=signin_username");
	}
	if(crypt($pass,'st') != $row['password']){
		header("Location: ../index.php?error=signin_username"); /* No need to tell the potential hacker which of the two was a problem */
	}

}



?>