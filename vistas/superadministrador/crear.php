<?php
require_once("../../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();
$empresa = $conectar->prepare("SELECT nit_empresa, nombre FROM empresas");
$empresa->execute();
$empresas = $empresa->fetchAll();  // Cambiado de fetch() a fetchAll()

if (isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "formreg") {
    // Obtener datos del formulario

    $nit_empresa = $_POST["nit_empresa"];
    $licencia = uniqid();
    $estado=2;

    $validar_nit = $conectar->prepare("SELECT * FROM licencias WHERE nit_empresa = ?");
    $validar_nit->execute([$nit_empresa]);
    

    if ($nit_empresa == "") {
        echo '<script>alert("EXISTEN CAMPOS VACÍOS");</script>';
        echo '<script>window location="regis.php"</script>';
    } 
    else {
        $insertsql = $conectar->prepare("INSERT INTO licencias ( licencia, id_estado, nit_empresa) VALUES (?, ?, ?)");
        $insertsql->execute([$licencia,$estado, $nit_empresa]);
        echo '<script>alert ("Registro exitoso");</script>';
        echo '<script> window.location= "licencia.php"</script>';
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
    <form method="post">
        <div class="form-group">
            <label for="empresa">Empresa:</label>
            <select class="form-control" id="nit" name="nit_empresa" required>
                <option value="" disabled selected>Selecciona la empresa</option> <!-- Placeholder -->
                <?php foreach ($empresas as $empresa) : ?>
                    <option value="<?php echo $empresa['nit_empresa']; ?>"><?php echo $empresa['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="MM_insert" value="formreg">
            <button type="submit">registrar</button>
        </div>
    </form>
</body>

</html>
