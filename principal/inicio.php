<?php

session_start();

require '../comunes/auxiliar.php';
$pdo = conectar();
opciones($_SESSION['login']);

comprobar_sesion();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio.</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Panel principal</h1>
    <?php if ($_SESSION['login'] == obtener_correo_admin($pdo)) {
        ?> <a href="../admin/comprobar_admin.php">Acceder como admin</a> <?php
    } ?>
    
</body>
</html>