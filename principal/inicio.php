<?php

session_start();

require '../comunes/auxiliar.php';
$pdo = conectar();
opciones($_SESSION['login']);

comprobar_sesion();

$pdo = conectar();
$email = obtener_email();
$id = obtener_id_usuario($pdo, $email);
$ejercicios = obtener_ejercicios_usuario($pdo, $id);
$actividades = obtener_actividades_usuario($pdo, $id);
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
            } else { ?>
            <section id="ejercicios_inicio">
                <h2>Mis ejercicios</h2>
                <?php if (!empty($ejercicios)) { ?>
                <div id="contenedor_ejercicios_inicio">
                    <?php
                    foreach ($ejercicios as $ejercicio) { ?>
                    <div class="ejercicio_inicio">
                        <h3><?= hh($ejercicio['ejercicio']) ?></h3>
                    </div>
                    <?php } ?>
                </div>
                <?php } else {
                    ?><h3 class="mensaje">No se han registrado ejercicios</h3> <?php
                } ?>
            </section>
            <section id="actividades_recientes_inicio">  
                <h2>Actividades más recientes</h2>
                <div id="contenedor_actividades">
                <?php 
                if (empty($actividades)) {
                    ?> <h3 class="mensaje">No hay Actividades registradas</h3>
                <?php } else {
                for ($x = 0; $x < 5; $x += 1) : 
                    $a = $actividades[$x];
                ?>
                
                <table class="actividad_inicio">
                    <thead>
                        <tr>
                            <th colspan="3"><?= hh($a['titulo']) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= hh($a['ejercicio']) ?></td>
                            <td><?= hh($a['fecha']) ?></td>
                            <td><?= hh($a['calorias']." kcl") ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php endfor;
                } ?>
                </div>
                <a href="actividades/subir_actividad.php" class="anadir">+</a>
                <a href="actividades/eliminar_actividad.php" class="eliminar">-</a>
            </section>
            <section id="evolucion_inicio">
                <h2>Evolución</h2>
                <?php if (empty($actividades)) {
                    ?><h3 class="mensaje">Sin evolución que mostrar</h3>
                <?php } else { ?>
                    <a href="evolucion/ver_evolucion.php" class="anadir">?</a>
                <?php } ?>
            </section>
            <?php
            } ?>
    </main>
</body>
</html>