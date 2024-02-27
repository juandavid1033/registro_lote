<?php
require_once("../../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();
$lista = $conectar->prepare("SELECT * FROM licencias,empresas,estados WHERE licencias.nit_empresa=empresas.nit_empresa AND licencias.id_estado=estados.id_estados ");
$lista->execute();
$listas = $lista->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Licencias</title>
</head>
<body>
    <a href="crear.php">Crear una licencia</a>
    <table>
        <thead>
            <tr>
                <th>Licencia</th>
                <th>Nombre</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listas as $lista) { ?>
                <tr>
                    <td><?= $lista["licencia"] ?></td>
                    <td><?= $lista["nombre"] ?></td>
                    <td><?= $lista["fecha_ini"] ?></td>
                    <td><?= $lista["fecha_fin"] ?></td>
                    <td><?= $lista["nom_estado"] ?></td>
                    <td>
                        <a href="activar_licencia.php?id=<?= $lista["licencia"] ?>" class="">Activar</a>
                        <a href="desactivar_licencia.php?id=<?= $lista["licencia"] ?>" class="">Desactivar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Botón de Cerrar Sesión -->
    <form action="../../index.html" method="post">
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>
