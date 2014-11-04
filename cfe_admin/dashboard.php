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
					<a href="#"><img src="img/CFE_LOGO.svg" alt="Comisión Federal de Electricidad"></a>
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
	<div id="contadores">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="card">
						<div class="title-card">HOY</div>
						<div class="new-fails"><span class="num">2</span><span class="text">Fallas</span></div>
						<div class="new-issues"><span class="num">5</span><span class="text">Quejas</span></div>
						<div class="foot-card">Nuevas</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="title-card">Último mes</div>
						<div class="new-fails"><span class="num">2</span><span class="text">Fallas</span></div>
						<div class="new-issues"><span class="num">5</span><span class="text">Quejas</span></div>
						<div class="foot-card">Recibidas</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="title-card">Último mes</div>
						<div class="new-fails"><span class="num">2</span><span class="text">Fallas</span></div>
						<div class="new-issues"><span class="num">5</span><span class="text">Quejas</span></div>
						<div class="foot-card">Resueltas</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="title-card">Último mes</div>
						<div class="new-fails"><span class="num">2</span><span class="text">Fallas</span></div>
						<div class="new-issues"><span class="num">5</span><span class="text">Quejas</span></div>
						<div class="foot-card">Procesadas</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="charts">
		<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="chart">
							<div class="chart-title">Total</div>
							<div id="container1" ></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="chart">
							<div class="chart-title">2014</div>
							<div id="container2" ></div>
						</div>
					</div>
				</div>
			</div>	
	</div>
	<div id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="search">
						<form class="search-form" action="">
							<label id="search-title" for="search-field">Número de Servicio</label>
							<input id="search-field" name="search-field" type="text" >
							<input id="btn-search" type="submit" value="Buscar">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//code.highcharts.com/highcharts.js"></script>
	<script src="js/funciones.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="js/funciones.js"></script>
</body>
</html>