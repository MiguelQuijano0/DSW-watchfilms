<?php
// Inicializar la sesión
session_start();
 
// Desactivar todas las variables de sesión
$_SESSION = array();
 
// Destruye la sesión.
session_destroy();
 
// Redirigir a la página de inicio de sesión
header("location: login.php");
exit;
?>