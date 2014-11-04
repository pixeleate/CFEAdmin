<?php session_start();
	
	function getConnection()
	{
		try{
			$db_username = "";
			$db_password = "";
			$connection = new PDO("mysql:;dbname=", $db_username, $db_password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
		return $connection;
	}
