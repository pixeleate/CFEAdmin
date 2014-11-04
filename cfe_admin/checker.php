<?php
include 'access.php'; // include the library for database connection

if(isset($_POST['action']) && $_POST['action'] == 'login'){ // Check the action `login`
	
	$email 		= htmlentities($_POST['email']); // Get the username
	$password 		= htmlentities($_POST['pass']); // Get the password and decrypt it
	
	try {

		$connection		= getConnection();
		$query 		= $connection->prepare('SELECT id, username FROM AdminAppUsers WHERE email = "'.$email.'" AND password = "'.$password.'" ');
		$query->execute();
		$num_rows		= $query->rowCount(); // Get the number of rows
		$user 		= $query->fetch(PDO::FETCH_ASSOC); //Get results with column names
		$connection = null;

		if($num_rows <= 0){ // If no users exist with posted credentials print 0 like below.
			echo 0;
		} else {
			// NOTE : We have already started the session in the library.php
			$_SESSION['userid'] 		= $user['id'];
			$_SESSION['username'] 	= $user['username'];
			echo 1;
		}
		
	} catch (Exception $e) {

		echo 'Error'.$e->getMessage();
		
	}
	
}
?>