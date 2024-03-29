<?php
// Verificar si se ha enviado el formulario de registro
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

    // Obtener los datos del formulario de registro
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, contraseña) VALUES (?, ?)";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        // Error en la preparación de la consulta
        echo "Error en la preparación de la consulta: " . $conn->error;
    } else {
        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("ss", $usuario, $contraseña);
        $result = $stmt->execute();
        
        // Verificar si la inserción fue exitosa
        if ($result === false) {
            // Error en la inserción
            echo "Error al registrar el usuario: " . $stmt->error;
        } else {
            // Usuario registrado exitosamente
            echo "Usuario registrado exitosamente";
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
    <title>Register form</title>
</head>
<body>
    <div class="login">
        <img src="img/cscloging.jpeg" alt="image" class="login__bg">
        <form action="register_user.php" method="post" class="login__form" id="registerForm">
            <h1 class="login__title">Register</h1>
            <div class="login__inputs">
                <div class="login__box">
                    <input type="text" name="usuario" placeholder="Usuario" required class="login__input">
                    <i class="ri-mail-fill"></i>
                </div>
                <div class="login__box">
                    <input type="password" name="contraseña" id="password" placeholder="Contraseña" required class="login__input">
                    <button type="button" id="togglePassword" class="login__show-password"><i class="ri-eye-fill"></i></button>
                    <i class="ri-lock-2-fill"></i>
                </div>
                <div class="login__box">
                    <input type="password" name="confirm_contraseña" id="confirmPassword" placeholder="Confirmar contraseña" required class="login__input">
                    <i class="ri-lock-2-fill"></i>
                </div>
            </div>
            <button type="submit" class="login__button">Register</button>
            <div class="login__register">
                <p>Already have an account? <a href="login.html">Login</a></p>
            </div>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const registerForm = document.getElementById('registerForm');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ri-eye-fill');
            this.querySelector('i').classList.toggle('ri-eye-off-fill');
        });

        registerForm.addEventListener('submit', function(event) {
            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
            }
        });
    </script>
</body>
</html>