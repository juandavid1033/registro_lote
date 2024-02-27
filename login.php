<?php
session_start();

require_once("db/conexion.php");
$db = new Database();
$conectar = $db->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login_button"])) {
    $documento = $_POST['documento'];
    $password = $_POST['contrasena'];


    try {
        $usuario = $conectar->prepare("SELECT * FROM usuario, licencias,estados WHERE licencias.nit_empresa = usuario.nit_empresa AND id_estado= 1 AND documento = ?");
        $usuario->execute([$documento]);
        $usuarios = $usuario->fetch();

        if ($usuarios && password_verify($password, $usuarios['contrasena'])) {
            $_SESSION['documento'] = $documento;
            $_SESSION['rol'] = $usuarios['id_rol'];

            switch ($_SESSION['rol']) {
                case 1:
                    header("Location: vistas/admin/index.php");
                    exit();
                case 2:
                    header("Location: vistas/vigilante/index.php");
                    exit();
                case 3:
                    header("Location: vistas/aprendiz/index.php");
                    exit();
                case 4:
                    header("Location: vistas/visitantes/index.php");
                    exit();
                case 5:
                    header("Location: vistas/superadministrador/licencia.php");
                    exit();
                default:
                    echo "<script>alert('La contraseña no es correcta o expiró su licencia');</script>";
                    echo '<script>window.location="index.html"</script>';
                    exit();
            }
        } else {
            echo "<script>alert('La contraseña no es correcta o expiró su licencia');</script>";
            echo '<script>window.location="index.html"</script>';
            exit();
        }
    } catch (PDOException $e) {
        // Manejar el error aquí (por ejemplo, loggearlo)
        echo "Error en la consulta: " . $e->getMessage();
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="shortcut icon" href="./src/img/camara-de-cctv.png" type="image/x-icon">
    <title>SIS - Login</title>
    <style>
        body {
            background-image: url('./src/img/fondo.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="documento">Documento:</label>
                        <input type="number" class="form-control" id="documento" name="documento" placeholder="Ingresa tu documento" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <div class="form-group text-center">
                        <a href="#" class="text-secondary">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="form-group text-center">
                        <a href="registro.php" class="text-secondary">Registro</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="login_button">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>