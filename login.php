<?php
// Inicializar la sesión
session_start();
// Comprobar si el usuario ya ha iniciado sesión, en caso afirmativo redirigirlo a la página de bienvenida
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
// Incluir fichero de configuración
require_once "Config.php";

// Definir variables e inicializar con valores vacíos
$username = $password = "";
$username_err = $password_err = "";

// Procesamiento de los datos del formulario cuando se envía
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Comprobar si el nombre de usuario está vacío
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese su usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Comprobar si la contraseña está vacía
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingrese su contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Validar  credenciales
    if(empty($username_err) && empty($password_err)){
        // Preparar una sentencia select
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la sentencia preparada como parámetros
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Establecer parámetros
            $param_username = $username;
            // Intento de ejecutar la sentencia preparada
            if(mysqli_stmt_execute($stmt)){
                // Almacenar resultado
                mysqli_stmt_store_result($stmt);
                // Comprueba si el nombre de usuario existe, si es así verifica la contraseña
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Vincular variables de resultado
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){//Verifica la contraseña y la cifra
                            // La contraseña es correcta, así que inicie una nueva sesión
                            session_start();
                            // Almacenar datos en variables de sesión
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                           // Redirigir al usuario a la página de bienvenida
                            header("location: index.php");
                        } else{
                            // Mostrar un mensaje de error si la contraseña no es válida
                            $password_err = "La contraseña que has ingresado no es válida.";
                        }
                    }
                } else{
                    // Mostrar un mensaje de error si el nombre de usuario no existe
                    $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                }
            } else{
                echo "Algo salió mal, por favor vuelve a intentarlo.";
            }
        }
        // Cerrar declaración
        mysqli_stmt_close($stmt);
    }
    // Cerrar la conexión
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Referencia al archivo CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/text.css">
    <link rel="stylesheet" href="css/aviso-cookie.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px;}
    </style>
    <script src="js/galleta.js"></script>
</head>
<body>
    <!-- Definir el formulario -->
    <div class="login-page">
        <div class="form">
        <h2>Iniciar Sesion</h2>
        <p>Por favor, complete sus credenciales para iniciar sesión.</p>
        <!-- Iniciar el envio de datos a la base de datos -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Imprimir un mensaje de error si el usuario es incorrecto -->
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <p>Usuario</p>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <!-- Imprimir un mensaje de error si la contraseña es incorrecta -->
                <p>Contraseña</p>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="button" value="Ingresar">
            </div>
            <!-- enviar al formulario de registro -->
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
        </form>
        </div>
    </div>
    <div class="aviso-cookies" id="aviso-cookies">
        <img class="galleta" src="./img/cookie.svg" alt="Galleta">
		<h2 class="titulo">Cookies</h2>
		<p class="parrafo">Utilizamos cookies propias y de terceros para mejorar nuestros servicios.</p>
		<button class="boton" id="btn-aceptar-cookies">De acuerdo</button>
		<a class="enlace" href="aviso-cookies.html">Aviso de Cookies</a>
	</div>
    <div class="fondo-aviso-cookies" id="fondo-aviso-cookies"></div>
    <script src="js/aviso-cookies.js"></script>
</body>
</html>