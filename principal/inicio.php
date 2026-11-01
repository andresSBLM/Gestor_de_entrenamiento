<?php

session_start();

require '../comunes/auxiliar.php';
$pdo = conectar();
opciones($_SESSION['login']);

comprobar_sesion();

$pdo = conectar();
$email = $_SESSION['login'];
$id = obtener_id_usuario($pdo, $email);
$ejercicios = obtener_ejercicios_usuario($pdo, $id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio.</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body id="body_inicio">
    <header id="cabecera_inicio">
        <h1>Panel principal</h1>
    </header>
    <main id="principal_inicio">
        <?php if ($_SESSION['login'] == obtener_correo_admin($pdo)) {
            ?> <a id="acceder_admin" href="../admin/comprobar_admin.php">Acceder como admin</a> <?php
            } else {
                ?>
            <section id="ejercicios_inicio">
                <h2>Mis ejercicios</h2>
                <div id="contenedor_ejercicios_inicio">
                    <?php
                    foreach ($ejercicios as $ejercicio) { ?>
                    <div id="ejercicio_inicio">
                        <h3><?= hh($ejercicio['ejercicio']) ?></h3>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <section id="subir_actividad_inicio">  
                <h2>Subir actividad</h2>
            </section>
            <section id="evolucion_inicio">
                <h2>Evolución</h2>
            </section>
            <?php } ?>
    </main>
</body>
</html>