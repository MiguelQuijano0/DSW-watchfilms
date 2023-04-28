<?php
// inicializa la sesion
session_start();
/*Valida si la secion esta iniciada y de lo contrario lo redirige al login*/
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="css/text.css">
</head>
<header>
	<img src="Whatchfilms-logo.png" width="100" height="70" alig="middle">
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li class="dropdown">
				<a href="#" class="dropbtn">Generos</a>
				<!-- Menu desplegabe de generos -->
				<div class="dropdown-content">
					<a href="Terror.html">Terror</a>
					<a href="comedia.html">Comedia</a>
					<a href="accion.html">Accion</a>
					<!-- Botones lateral de configuracion de sesion -->
					<li style="float:right"><a href="logout.php" class="btn btn-danger">Cierra la sesión</a></li>
					<li style="float:right"><a href="reset-password.php" class="btn btn-warning">Cambia tu contraseña</a></li>
				</div>
			</li>
			<li><a href="products.php">Tienda</a></li>
            <li><a href="about.html">Acerca de</a></li>
            <li><a href="contacto.html">Contacto</a></li>
		</ul>
	</nav>
</header>
<body>
	<div class="page-header">
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenid@ a nuestro sitio.</h1>
    </div>
    <section>
		<h3 aling="center"> En esta pagina podras consutar recomendaciones sobre Peliculas y series, para poder
			disfrutarlas,
			calificarlas y compartirlas con tus familiares y amigo, podras llevar un control de tus Peliculas y
			darles un calificacion<br>
		</h2>
		<article>
			<img src="https://cinescopia.com/wp-content/uploads/2016/03/peliculas.jpg" width="1330" height="700">
		</article>
	</section>
</body>
<footer>
	<br>
	<p><b>CONTACTENOS</b></p>
	<a href="">
		<p>Facebook</p>
	</a>
	<p>
		Whatchfilms@films.com
	</p>
	<br>
</footer>
</html>