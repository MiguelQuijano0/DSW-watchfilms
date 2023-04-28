<!-- Archivo de configuracion de conexion entre las paginas de registro, login y la base de datos-->
<?php
/* Credenciales de la base de datos. Asumiendo que está ejecutando MySQL
con la configuración por defecto (usuario 'root' sin contraseña) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'demo');

/* Intento de conexión a base de datos MySQL */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Comprobar conexión
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>