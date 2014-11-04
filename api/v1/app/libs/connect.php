<?php if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

function getConnection()
{
	try{
		$db_username = "";
		$db_password = "";
		$connection = new PDO("mysql:host=;dbname=", $db_username, $db_password);
		//$connection = new PDO("mysql:host=localhost:8888;dbname=mtour_cfeapp", $db_username, $db_password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	return $connection;
}