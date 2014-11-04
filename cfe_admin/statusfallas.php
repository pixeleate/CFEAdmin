<?php 
	//include 'access.php'; 
	///if(!isset($_SESSION['userid'])){ // Redirect to secured user page if user logged in
		///echo '<script type="text/javascript">window.open("index.php","_self"); </script>';
	//}
function getConnection()
	{
		try{
			$db_username = "";
			$db_password = "";
			$connection = new PDO("mysql:host=;dbname=", $db_username, $db_password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
		return $connection;
	}

function sendPush($message,$token){

	//curl -X POST -F "channel=test" -F "to_tokens=APA91bEd1unh732ytln8fez7SKWJW0sAp_sHGYWRSjCqXkFclQHpyC0eUQVTnybHtv_PaaL9pVafZkPxAyq8AEhTWLnWhPbIjwMvmX3bQuFkq8L1oFuYNqjanl98Gt9on5vcgXfbLMTvKYSKvmuNfVzxNMUNK6macw" -F "payload=CFE APP" "https://api.cloud.appcelerator.com/v1/push_notification/notify_tokens.json?key=C7YNclPOgyJv3HDvv6IstJKwyiO663gj&pretty_json=true"

    $curlObj    = curl_init();
    $c_opt      = array(CURLOPT_URL => 'https://api.cloud.appcelerator.com/v1/push_notification/notify_tokens.json?key=C7YNclPOgyJv3HDvv6IstJKwyiO663gj', 
                        CURLOPT_RETURNTRANSFER => true, 
                        CURLOPT_CUSTOMREQUEST, "POST",
                        CURLOPT_POSTFIELDS  =>  "channel=test&payload=".$message."&to_tokens=".$token
                        );
    curl_setopt_array($curlObj, $c_opt); 
    $session = curl_exec($curlObj);     
    /*** THE END ********************************************/
    curl_close($curlObj);

    header('Content-Type: application/json');
    die(json_encode(array('response' => json_decode($session))));
    
}


$folio = $_POST["f"];
$no_servicio = $_POST["n"];
$fecha_reporte = $_POST["d"];
$estatus = $_POST["e"];

if($estatus == 'Abierta'){

	try {

		$connection		= getConnection();
		$query 		= $connection->prepare("UPDATE fallasApp SET estatusFalla = 'En proceso', fechaResuelta = NOW() WHERE no_servicio = ? AND folio = ? AND created_at = ?");
		$query->bindParam(1, $no_servicio);
		$query->bindParam(2, $folio);
		$query->bindParam(3, $fecha_reporte);
		$query->execute();
		$connection = null;

		
	} catch (Exception $e) {

		echo 'Error'.$e->getMessage();
		
	}

	try {

		$connection		= getConnection();
		$query 		= $connection->prepare("Select u.no_servicio, f.folio, t.token from usersApp u inner join tokensApp t on u.username = t.username inner join fallasApp f on f.no_servicio = u.no_servicio WHERE u.no_servicio = ? AND f.folio = ? AND f.created_at = ?");
		$query->bindParam(1, $no_servicio);
		$query->bindParam(2, $folio);
		$query->bindParam(3, $fecha_reporte);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$connection = null;

		$message = 'Tu reporte con el folio: '.$folio.' ha cambiado su estatus a: En proceso';
		$token = $result['token'];

		sendPush($message,$token);

		
	} catch (Exception $e) {

		echo 'Error'.$e->getMessage();
		
	}

}elseif ($estatus == 'En proceso') {
	try {

		$connection		= getConnection();
		$query 		= $connection->prepare("UPDATE fallasApp SET estatusFalla = 'Atendida', fechaResuelta = NOW() WHERE no_servicio = ? AND folio = ? AND created_at = ?");
		$query->bindParam(1, $no_servicio);
		$query->bindParam(2, $folio);
		$query->bindParam(3, $fecha_reporte);
		$query->execute();
		$connection = null;

		
	} catch (Exception $e) {

		echo 'Error'.$e->getMessage();
		
	}

	try {

		$connection		= getConnection();
		$query 		= $connection->prepare("Select u.no_servicio, f.folio, t.token from usersApp u inner join tokensApp t on u.username = t.username inner join fallasApp f on f.no_servicio = u.no_servicio WHERE u.no_servicio = ? AND f.folio = ? AND f.created_at = ?");
		$query->bindParam(1, $no_servicio);
		$query->bindParam(2, $folio);
		$query->bindParam(3, $fecha_reporte);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$connection = null;

		$message = 'Tu reporte con el folio: '.$folio.' ha cambiado su estatus a: Atendida';
		$token = $result['token'];

		sendPush($message,$token);

		
	} catch (Exception $e) {

		echo 'Error'.$e->getMessage();
		
	}
}
 ?>