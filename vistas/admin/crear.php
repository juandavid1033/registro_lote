<?php
require_once("../../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();

if ((isset($_POST["enviar"])) && ($_POST["enviar"] == "formulario")) {
    $nit_empresa = $_POST['nit_empresa'];
    $nombre_empre = $_POST['nombre_empresa'];
    $telefono = $_POST['telefono_empresa'];

    // Verificar si ya existe un registro con el mismo NIT
    $validacion = $conectar->prepare("SELECT * FROM empresas WHERE nit_empresa = ?");
    $validacion->execute([$nit_empresa]);
    $nit_existente = $validacion->fetch();

    if ($nit_existente) {
        echo '<script>alert("Ya existe una empresa con ese NIT.");</script>';
        echo '<script>window.location="formulario_registro.php"</script>';
    } else {
        // Insertar los datos en la base de datos
        $insertsql = $conectar->prepare("INSERT INTO empresas (nit_empresa, telefono, nombre) VALUES (?, ?, ?)");
        $insertsql->execute([$nit_empresa, $telefono, $nombre_empre]);

        echo '<script>alert("Registro exitoso.");</script>';
        echo '<script>window.location="empresas.php"</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
        <div class="container mt-5">
            <h2>Registro de Empresa</h2>
            <form method="POST">
                <div>
                    <label for="nit_empresa">NIT:</label>
                    <input type="number" id="nit_empresa" name="nit_empresa" required>
                </div>
                <div>
                    <label for="telefono_empresa">Teléfono:</label>
                    <input type="text" id="telefono_empresa" name="telefono_empresa" required>
                </div>
                <div>
                    <label for="nombre_empresa">Nombre de la Empresa:</label>
                    <input type="text" id="nombre_empresa" name="nombre_empresa" required>
                </div>
                <button type="submit">Registrar</button>
                <input type="hidden" name="enviar" value="formulario">
                <a href="empresas.php">Cancelar</a>
            </form>
        </div>

</body>

</html>