<?php 
	include 'access.php'; 
	if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in
		echo '<script type="text/javascript">window.open("dashboard.php","_self"); </script>';
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin App CFE </title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Cinzel:400,700,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

	<div class="container">
		<header class="row">
			<div id="logo_dash" class="col-md-6">
				<a href="#"><img src="img/CFE_LOGO.svg" alt="Comisión Federal de Electricidad"></a>
			</div>
			<div id="ubication" class="col-md-6">
				<div class="title_page">
					<h2>Dashboard</h2>
					<h2>Comisión Federal de Electricidad</h2>	
				</div>
			</div>
		</header>
	</div>
	<!--<div id="fullscreen_bg" class="fullscreen_bg"/>-->
	<div class="container ">
		<div class="login_result"></div>
		<div class="login-form col-md-4 col-md-offset-4">
			<form class="form-signin">
				<h1 class="form-signin-heading text-muted">Login</h1>
				<label for="email">Correo Electrónico</label>
				<input id="email" type="text" class="form-control" placeholder="Correo Electrónico" required="" autofocus="">
				<label for="password">Contraseña</label>
				<input id="password" type="password" class="form-control" placeholder="Contraseña" required="">
				<button id="login" class="btn btn-lg btn-primary btn-block" type="submit">
					Iniciar Sesión
				</button>
			</form>
		</div>
		
	</div>
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="js/funciones.js"></script>
</body>
</html>