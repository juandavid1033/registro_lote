<?php
require_once("../../db/conexion.php");
$db = new Database();
$conectar = $db->conectar();
session_start();


if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $nit_empresa = $conectar->prepare("SELECT licencia FROM licencias WHERE licencia=?");
    $nit_empresa->execute([$id]);
    $nit_empre = $nit_empresa->fetch(PDO::FETCH_ASSOC);


    $fecha_inicio = date('Y-m-d H:i:s');

    $fecha_fin = date('Y-m-d H:i:s', strtotime('+2 year'));

    $estado=1;




    if ($nit_empre) {
        $updateSql = $conectar->prepare("UPDATE licencias SET fecha_ini = ?, fecha_fin = ?, id_estado = ? WHERE licencia = ?");
        $updateSql->execute([$fecha_inicio, $fecha_fin,$estado, $id]);

        echo '<script>alert("Licencia activa.");</script>';
        echo '<script>window.location="licencia.php"</script>';
    }
}
