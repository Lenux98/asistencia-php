<?php
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (reemplaza estos valores con los tuyos)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "usersdb";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer la codificación de caracteres
    mysqli_set_charset($conn, "utf8");

    // Obtener los datos del formulario y escaparlos
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        // Error en la preparación de la consulta
        echo "Error en la preparación de la consulta: " . $conn->error;
    } else {
        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("ss", $usuario, $contraseña);
        $stmt->execute();
        
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        
        // Verificar si se encontró un usuario con las credenciales proporcionadas
        if ($result->num_rows > 0) {
            // Usuario válido, establecer sesión y redirigir al menú de opciones
            $_SESSION['usuario'] = $usuario;
            header("Location: employees.php");
            exit;
        } else {
            // Usuario inválido, mostrar mensaje de error
            $error_message = "Usuario o contraseña incorrectos";
        }
        
        // Cerrar la consulta
        $stmt->close();
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login form</title>
</head>
<body>
    <div class="login">
        <img src="img/cscloging.jpeg" alt="image" class="login__bg">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login__form">
            <h1 class="login__title">Login</h1>
            <div class="login__inputs">
                <div class="login__box">
                    <input type="text" name="usuario" placeholder="Usuario" required class="login__input">
                    <i class="ri-mail-fill"></i>
                </div>
                <div class="login__box">
                    <input type="password" name="contraseña" placeholder="Contraseña" required class="login__input">
                    <i class="ri-lock-2-fill"></i>
                </div>
            </div>
            <?php if(isset($error_message)) { ?>
                <div class="login__error"><?php echo $error_message; ?></div>
            <?php } ?>
            <button type="submit" class="login__button">Login</button>
            <div class="login__register">
                Don't have an account? <a href="register_user.php">Register</a>
            </div>
        </form>
    </div>
</body>
</html>
