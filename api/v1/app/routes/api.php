<?php
if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

$app->post("/registro/", function() use($app)
{

/*
{
"user":
        {
        "email":"ugarcia@u.com",
        "name":"ulises",
        "phone":"1117677",
        "rpu": "375262054121",
        "username":"linux",
        "pass":"yes"
        }
}
*/

	$body = $app->request()->getBody();

	$json = json_decode($body,true);

	$email = $json['user']['email'];
	$full_name = $json['user']['full_name'];
	$phone = $json['user']['phone'];
	$no_servicio = $json['user']['no_servicio'];
	$username = strtolower( $json['user']['username'] );
	$pass = password_hash( $json['user']['pass'], PASSWORD_BCRYPT );

	$user_registred = '';


	try{
		$connection = getConnection();
		$dbh = $connection->prepare("SELECT COUNT(*) AS user_registred FROM usersApp WHERE username = ? OR no_servicio = ? OR email = ?");
		$dbh->bindParam(1, $username);
		$dbh->bindParam(2, $no_servicio);
		$dbh->bindParam(3, $email);
		$dbh->execute();
		$result = $dbh->fetch(PDO::FETCH_ASSOC);
		$user_registred = $result['user_registred'];
		$connection = null;
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

	if($user_registred == 0){

		try{
			$connection = getConnection();
			$dbh = $connection->prepare("Insert INTO usersApp VALUES('', ?, ?, ?, ?, ?, ?, NOW() )");
			$dbh->bindParam(1, $email);
			$dbh->bindParam(2, $full_name);
			$dbh->bindParam(3, $phone);
			$dbh->bindParam(4, $no_servicio);
			$dbh->bindParam(5, $username);
			$dbh->bindParam(6, $pass);
			$dbh->execute();
			$connection = null;

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("mensaje" => array("success" => "Usuario creado correctamente" ))));
		}
		catch(PDOException $e)
		{
			//echo "Error: " . $e->getMessage();
			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "El usuario no puede ser creado, intente de nuevo" ))));
		}

	}else{
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(400);
		$app->response->body(json_encode(array("mensaje" => array("error" => "El usuario ya existe, o el email y número de servicio están asignados a otro usuario" ))));

	}
});

$app->post("/login/", function() use($app)
{

/*
{
"login":
        {
        "username":"linux",
        "pass":"yes",
        "token_id":"XXXXx"
        }
}
*/

	$body = $app->request()->getBody();

	$json = json_decode($body,true);

	$username = strtolower( $json['login']['username'] );
	$pass = $json['login']['pass'];
	$token = $json['login']['token_id'];

	$user_exist = '';

	try{
		$connection = getConnection();
		$dbh = $connection->prepare("SELECT count(idToken) AS 'user_exist' FROM tokensApp WHERE username = ?");
		$dbh->bindParam(1, $username);
		$dbh->execute();
		$result = $dbh->fetch(PDO::FETCH_ASSOC);
		$user_exist = $result['user_exist'];
		$connection = null;
		
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}


	if ($user_exist == 0) {

		try{
			$connection = getConnection();
			$dbh = $connection->prepare("INSERT INTO tokensApp VALUES('',?,?,NOW())");
			$dbh->bindParam(1, $username);
			$dbh->bindParam(2, $token);
			$dbh->execute();
			$connection = null;
		
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
		
	}else{

		try{
			$connection = getConnection();
			$dbh = $connection->prepare("UPDATE tokensApp t SET t.token = ? WHERE username = ?");
			$dbh->bindParam(1, $token);
			$dbh->bindParam(2, $username);
			$dbh->execute();
			$connection = null;
		
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}

	}

	try{
		$connection = getConnection();
		$dbh = $connection->prepare("SELECT email, full_name, phone, no_servicio, username, pass FROM usersApp WHERE username = ?");
		$dbh->bindParam(1, $username);
		$dbh->execute();
		$result = $dbh->fetch(PDO::FETCH_ASSOC);
		$connection = null;
		if (password_verify($pass, $result['pass'])) {

	    	$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("mensaje" => array("success" => "Acceso correcto, bienvenido a CFEMovil" ), "datos"=> array("full_name" => $result['full_name'], "email" => $result['email'], "phone" => $result['phone'], "no_servicio" => $result['no_servicio'], "username" => $result['username']))));

		} else {

		    $app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "Usuario o Contraseña incorrectos" ))));
		}
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "Por el momento no es posible iniciar sesión, intente más tarde" ))));
	}


});	

$app->post("/SELAclaraRecibo", function() use($app)
{

	class AccesoCliente{
		public $UsuarioMovil;
		public $PasswordMovil;
		public $SistemaOperativo ;
		public $VersionSO ;
		public $TipoEquipo;
		public $ModeloEquipo;
		public $ResolucionEquipo;
		public $Ip;
		public $Ubicacion;
		public $Hash;
	}

	$wsdl = "http://aplicaciones.cfe.gob.mx/WebServices/CFEMovil/CFEMovil.svc?wsdl";

	try {
		
		$sClient = new SoapClient($wsdl);

		$Llave = 'vlw3xMqy9LNjQMs5rE4z';

		$param = new AccesoCliente();
		$param->UsuarioMovil = 'usrReto071';
		$param->PasswordMovil = '@R3t0C3r053t3nt4yUn0@';
		$param->SistemaOperativo = 'IOS';
		$param->VersionSO = '8.0';
		$param->TipoEquipo = 'iPhone';
		$param->ModeloEquipo = 'iphone6';
		$param->ResolucionEquipo = '300x300';
		$param->Ip = '1.1.1.1';
		$param->Ubicacion = '20.0000,-100.0000';
		$Correo = 'usuariotmp@example.com ';
		$Rpu = '375262054121';
		$Observaciones = 'Favor de revisar el servicio';
		$param->Hash = hash('sha1',$param->UsuarioMovil.$param->PasswordMovil.$param->SistemaOperativo.$param->VersionSO.$param->TipoEquipo.$param->ModeloEquipo.$param->Ip.$Rpu.$Correo.$Observaciones.$Llave);

		$retVal =$sClient->SELAclaraRecibo(array('Acceso' => $param, 'Rpu'=> $Rpu, 'Correo'=> $Correo, 'Observaciones'=> $Observaciones));

		echo json_encode($retVal->SELAclaraReciboResult);

	} catch (SoapFault $e) {
		echo $e->faultstring;
	}
	
});

$app->get("/SELRevisaMedidor", function() use($app)
{

	class AccesoCliente{
		public $UsuarioMovil;
		public $PasswordMovil;
		public $SistemaOperativo ;
		public $VersionSO ;
		public $TipoEquipo;
		public $ModeloEquipo;
		public $ResolucionEquipo;
		public $Ip;
		public $Ubicacion;
		public $Hash;
	}

	$wsdl = "http://aplicaciones.cfe.gob.mx/WebServices/CFEMovil/CFEMovil.svc?wsdl";

	try {
		
		$sClient = new SoapClient($wsdl);

		$Llave = 'vlw3xMqy9LNjQMs5rE4z';

		$param = new AccesoCliente();
		$param->UsuarioMovil = 'usrReto071';
		$param->PasswordMovil = '@R3t0C3r053t3nt4yUn0@';
		$param->SistemaOperativo = 'IOS';
		$param->VersionSO = '8.0';
		$param->TipoEquipo = 'iPhone';
		$param->ModeloEquipo = 'iphone6';
		$param->ResolucionEquipo = '300x300';
		$param->Ip = '1.1.1.1';
		$param->Ubicacion = '20.0000,-100.0000';
		$Correo = 'usuariotmp@example.com ';
		$Rpu = '375262054121';
		$Observaciones = 'Favor de revisar el servicio';
		$param->Hash = hash('sha1',$param->UsuarioMovil.$param->PasswordMovil.$param->SistemaOperativo.$param->VersionSO.$param->TipoEquipo.$param->ModeloEquipo.$param->Ip.$Rpu.$Correo.$Observaciones.$Llave);

		$retVal =$sClient->SELRevisaMedidor(array('Acceso' => $param, 'Rpu'=> $Rpu, 'Correo'=> $Correo, 'Observaciones'=> $Observaciones));

		//print_r($retVal);

		echo json_encode($retVal->SELRevisaMedidorResult);

	} catch (SoapFault $e) {
		echo $e->faultstring;
	}
	
});

$app->post("/CFEReporteFallas", function() use($app)
{
/*
{
"falla":
        {
        "no_servicio":"",
        "email":"",
        "direccion":"",
        "coordenadas": "",
        "descripcion":"",
        "tipo_falla":""
        }
}
*/
	$body = $app->request()->getBody();

	$json = json_decode($body,true);

	$no_servicio = $json['falla']['no_servicio'];
	$email = $json['falla']['email'];
	$direccion = $json['falla']['direccion'];
	$coordenadas = $json['falla']['coordenadas'];
	$descripcion = $json['falla']['descripcion'];
	$tipo_falla = $json['falla']['tipo_falla'];

	try{
		$connection = getConnection();
		$dbh = $connection->prepare("INSERT INTO fallasApp VALUES('', 'D20315193', ?, ?, ?, ?,?,'Abierta',?,'N/A', NOW() )");
		$dbh->bindParam(1, $no_servicio);
		$dbh->bindParam(2, $email);
		$dbh->bindParam(3, $direccion);
		$dbh->bindParam(4, $coordenadas);
		$dbh->bindParam(5, $descripcion);
		$dbh->bindParam(6, $tipo_falla);
		$dbh->execute();
		$connection = null;

    	$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		$app->response->body(json_encode(array("mensaje" => array("success" => "Operación completada correctamente", "folio"=> 'D20315193' ))));
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se puede registrar la falla, datos incompletos" ))));
	}

});

$app->get("/SELReporteFallas", function() use($app)
{

	class AccesoCliente{
		public $UsuarioMovil;
		public $PasswordMovil;
		public $SistemaOperativo ;
		public $VersionSO ;
		public $TipoEquipo;
		public $ModeloEquipo;
		public $ResolucionEquipo;
		public $Ip;
		public $Ubicacion;
		public $Hash;
	}

	$wsdl = "http://aplicaciones.cfe.gob.mx/WebServices/CFEMovil/CFEMovil.svc?wsdl";

	try {
		
		$sClient = new SoapClient($wsdl);

		$Llave = 'vlw3xMqy9LNjQMs5rE4z';

		$param = new AccesoCliente();
		$param->UsuarioMovil = 'usrReto071';
		$param->PasswordMovil = '@R3t0C3r053t3nt4yUn0@';
		$param->SistemaOperativo = 'iOS';
		$param->VersionSO = '8.1';
		$param->TipoEquipo = 'IPhone';
		$param->ModeloEquipo = '6';
		$param->ResolucionEquipo = '300x300';
		$param->Ip = '201.124.20.10';
		$param->Ubicacion = '20.0000,-100.0000';
		$Correo = 'usuariotmp@example.com';
		$Rpu = '375262054121';
		$Observaciones = 'Favor de revisar el servicio';
		$TipoFalla = '08';
		$param->Hash = hash('sha1',$param->UsuarioMovil.$param->PasswordMovil.$param->SistemaOperativo.$param->VersionSO.$param->TipoEquipo.$param->ModeloEquipo.$param->Ip.$Rpu.$Correo.$Observaciones.$TipoFalla.$Llave);

		$retVal =$sClient->SELReporteFallas(array('Acceso' => $param, 'Rpu'=> $Rpu, 'Correo'=> $Correo, 'Observaciones'=> $Observaciones, 'TipoFalla' => $TipoFalla));

		echo json_encode($retVal->SELReporteFallasResult);

	} catch (SoapFault $e) {
		echo $e->faultstring;
	}
	
});

$app->post("/CFEReporteQuejas", function() use($app)
{

/*
{
"queja":
        {
        "no_servicio":"",
        "email":"",
        "coordenadas": "",
        "descripcion":"",
        "tipo_queja":""
        }
}
*/
	$body = $app->request()->getBody();

	$json = json_decode($body,true);

	$no_servicio = $json['queja']['no_servicio'];
	$email = $json['queja']['email'];
	$coordenadas = $json['queja']['coordenadas'];
	$descripcion = $json['queja']['descripcion'];
	$tipo_falla = $json['queja']['tipo_queja'];

	try{
		$connection = getConnection();
		$dbh = $connection->prepare("INSERT INTO quejasApp VALUES('', 'D20315192', ?, ?, ?, ?,'Abierta',?,'N/A', NOW() )");
		$dbh->bindParam(1, $no_servicio);
		$dbh->bindParam(2, $email);
		$dbh->bindParam(3, $coordenadas);
		$dbh->bindParam(4, $descripcion);
		$dbh->bindParam(5, $tipo_falla);
		$dbh->execute();
		$connection = null;

    	$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		$app->response->body(json_encode(array("mensaje" => array("success" => "Operación completada correctamente", "folio"=> 'D20315192' ))));
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se puede registrar la queja, datos incompletos" ))));
	}

	
});

$app->get("/CFEFallas/:no_servicio", function($no_servicio) use($app)
{
	try{

		$rows = array();

		$connection = getConnection();
		$dbh = $connection->prepare("select folio, created_at as 'fecha_recepcion', descripcion, estatusFalla as 'estatus_falla', fechaResuelta as 'fecha_resuelta' from fallasApp f where f.no_servicio = ?");
		$dbh->bindParam(1, $no_servicio);
		$dbh->execute();
		$result = $dbh->fetchAll(PDO::FETCH_ASSOC);
		$connection = null;

		foreach ($result as $key => $value) {
			$rows[] = $value;
		}

		//print_r(count($result));

		if(count($result) != 0){

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode($rows));

		}else{

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "No hay existen fallas registradas para ese número de servicio" ))));

		}

		// print_r($result);
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se pueden recuperar las fallas" ))));
	}

	
});

$app->get("/CFEQuejas/:no_servicio", function($no_servicio) use($app)
{

	try{
		$rows = array();

		$connection = getConnection();
		$dbh = $connection->prepare("select folio, created_at as 'fecha_recepcion', descripcion, estatusQueja as 'estatus_queja', fechaResuelta as 'fecha_resuelta' from quejasApp f where f.no_servicio = ?");
		$dbh->bindParam(1, $no_servicio);
		$dbh->execute();
		$result = $dbh->fetchAll(PDO::FETCH_ASSOC);
		$connection = null;

		//print_r(count($result));

		foreach ($result as $key => $value) {
			$rows[] = $value;
		}

		if(count($result) != 0){

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode($rows));

		}else{

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "No hay existen fallas registradas para ese número de servicio" ))));

		}

		//print_r($result);
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se pueden recuperar las fallas" ))));
	}

	
});

$app->get("/CFEFallasMap", function() use($app)
{

	try{
		$rows = array();

		$connection = getConnection();
		$dbh = $connection->prepare("SELECT 
	SUBSTRING_INDEX(f.coordenadas, ',', 1) as latitude, 
	SUBSTRING_INDEX(f.coordenadas, ',', -1) as longitude, 
	concat('<div class=info-window-styled><div class=m-f-recibida>Recibida: ',f.created_at,'</div><div class=m-folio>No. Folio: ', f.folio,'</div><div class=m-no-servicio> Número de servicio: ', f.no_servicio,'</div><div class=m-desc>Descripción: ', f.descripcion,'</div><div class=m-status>Estatus: ', f.estatusFalla,'</div><div class=m-f-resuelta>Resuelta: ', f.fechaResuelta,'</div></div>') as 'content',  f.created_at as 'fechaReporte', f.folio, f.no_servicio, f.descripcion, f.estatusFalla, f.fechaResuelta FROM fallasApp f");
		$dbh->execute();
		$result = $dbh->fetchAll(PDO::FETCH_ASSOC);
		$connection = null;

		//print_r(count($result));

		foreach ($result as $key => $value) {
			$rows[] = $value;
		}


		if(count($result) != 0){

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("markers" => $rows)));

		}else{

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "No hay existen fallas registradas" ))));

		}

		//print_r($rows);
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se pueden recuperar las fallas" ))));
	}

	
});

$app->get("/CFEFallasDash", function() use($app)
{

	try{
		$rows = array();

		$connection = getConnection();
		$dbh = $connection->prepare("SELECT f.folio, f.created_at as 'fechaReporte', f.fechaResuelta,  f.no_servicio, f.descripcion, f.estatusFalla FROM fallasApp f order by f.created_at DESC LIMIT 4");
		$dbh->execute();
		$result = $dbh->fetchAll(PDO::FETCH_ASSOC);
		$connection = null;

		//print_r(count($result));

		foreach ($result as $key => $value) {
			$rows[] = $value;
		}


		if(count($result) != 0){

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("fallas" => $rows)));

		}else{

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "No hay existen fallas registradas" ))));

		}

		//print_r($rows);
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se pueden recuperar las fallas" ))));
	}

	
});

$app->get("/CFEQuejasDash", function() use($app)
{

	try{
		$rows = array();

		$connection = getConnection();
		$dbh = $connection->prepare("SELECT f.folio, f.created_at as 'fechaReporte', f.fechaResuelta,  f.no_servicio, f.descripcion, f.estatusQueja FROM quejasApp f order by f.created_at DESC LIMIT 5");
		$dbh->execute();
		$result = $dbh->fetchAll(PDO::FETCH_ASSOC);
		$connection = null;

		//print_r(count($result));

		foreach ($result as $key => $value) {
			$rows[] = $value;
		}


		if(count($result) != 0){

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("fallas" => $rows)));

		}else{

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(400);
			$app->response->body(json_encode(array("mensaje" => array("error" => "No hay existen quejas registradas" ))));

		}

		//print_r($rows);
		
	}
	catch(PDOException $e)
	{
		//echo "Error: " . $e->getMessage();
		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(500);
		$app->response->body(json_encode(array("mensaje" => array("error" => "No se pueden recuperar las fallas" ))));
	}

	
});


$app->get("/SELConsultaSolicitud", function() use($app)
{

	class AccesoCliente{
		public $UsuarioMovil;
		public $PasswordMovil;
		public $SistemaOperativo ;
		public $VersionSO ;
		public $TipoEquipo;
		public $ModeloEquipo;
		public $ResolucionEquipo;
		public $Ip;
		public $Ubicacion;
		public $Hash;
	}

	$wsdl = "http://aplicaciones.cfe.gob.mx/WebServices/CFEMovil/CFEMovil.svc?wsdl";

	try {
		
		$sClient = new SoapClient($wsdl);

		$Llave = 'vlw3xMqy9LNjQMs5rE4z';

		$param = new AccesoCliente();
		$param->UsuarioMovil = 'usrReto071';
		$param->PasswordMovil = '@R3t0C3r053t3nt4yUn0@';
		$param->SistemaOperativo = 'IOS';
		$param->VersionSO = '8.0';
		$param->TipoEquipo = 'iPhone';
		$param->ModeloEquipo = 'iphone6';
		$param->ResolucionEquipo = '300x300';
		$param->Ip = '1.1.1.1';
		$param->Ubicacion = '20.0000,-100.0000';
		$Correo = 'usuariotmp@example.com ';
		$Rpu = '375262054121';
		$Observaciones = 'Favor de revisar el servicio';
		$NumeroSolicitud = 'D20315193';
		$IdEstado = '1';
		$IdMunicipio = '1';
		$param->Hash = hash('sha1',$param->UsuarioMovil.$param->PasswordMovil.$param->SistemaOperativo.$param->VersionSO.$param->TipoEquipo.$param->ModeloEquipo.$param->Ip.$NumeroSolicitud.$Correo.$IdEstado.$IdMunicipio.$Llave);

		$retVal =$sClient->SELConsultaSolicitud(array('Acceso' => $param, 'NumeroSolicitud'=> $NumeroSolicitud, 'Correo'=> $Correo, 'IdEstado'=> $IdEstado, 'IdMunicipio' => $IdMunicipio));

		//print_r($retVal);

		echo json_encode($retVal->SELConsultaSolicitudResult);

	} catch (SoapFault $e) {
		echo $e->faultstring;
	}
	
});

$app->get("/cfemovil/", function() use($app)
{
	$wsdl = "http://aplicaciones.cfe.gob.mx/WebServices/CFEMovil/CFEMovil.svc?wsdl";

echo '<pre>';
	try {
		$xConverter = new SoapClient($wsdl);
		echo "Types:\n";
		if ($xTypes = $xConverter->__getTypes()) {
			foreach ($xTypes AS $type) {

				$type = preg_replace(
				array('/(\w+) ([a-zA-Z0-9]+)/', '/\n /'),
				array('<font color="green">${1}</font> <font color="blue">${2}</font>', "\n\t"),
				$type
				);
				echo $type;
				echo "\n\n";
			}
		}
		echo "Functions:\n";
		if ($xTypes = $xConverter->__getFunctions()) {
			foreach ($xTypes AS $type) {

				$type = preg_replace(
				array('/(\w+) ([a-zA-Z0-9]+)/', '/\n /'),
				array('<font color="green">${1}</font> <font color="blue">${2}</font>', "\n\t"),
				$type
				);
				echo $type;
				echo "\n\n";
			}
		}
	} catch (SoapFault $e) {
		var_dump($e);
	}
echo '</pre>';
});