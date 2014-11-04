<?php 
	include 'access.php'; 
	if(!isset($_SESSION['userid'])){ // Redirect to secured user page if user logged in
		echo '<script type="text/javascript">window.open("index.php","_self"); </script>';
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<div id="header">	
		<div class="container">
			<header>
				<div id="logo_dash" class="col-md-6">
					<a href="index.php"><img src="img/CFE_LOGO.svg" alt="Comisión Federal de Electricidad"></a>
				</div>
				<div id="ubication" class="col-md-6">
					<div id="user">
						<span id="label_username">Usuario:</span>
						<span id="username"><?php echo $_SESSION['username']; ?></span>
						<a id="btn_logout" href="logout.php">Cerrar Sesión</a>
					</div>
					<div class="title_page">
						<h2>Dashboard</h2>
						<h2>Comisión Federal de Electricidad</h2>	
					</div>
				</div>
			</header>
		</div>
	</div>
	<div id="navigation">
		<nav class="container">
			<div class="col-md-6"><a href="quejas.php">Quejas</a></div>
			<div class="col-md-6"><a href="fallas.php">Fallas</a></div>
		</nav>
	</div>
	<div id="title-map">
		<div class="container">
			<div class="col-md-4 col-md-offset-4">
				<p>Ubicación de las fallas</p>
			</div>
		</div>
	</div>
	
	<div id="map">
		<div class="container">
			<div class="col-md-12">
				<div id="fails-map" class="gmap3"></div>
			</div>
		</div>
	</div>
	

		
	
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&amp;language=es"></script>
	<script src="js/jquery.ui.map.js"></script>
	<script src="js/func-maps.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</body>
</html>