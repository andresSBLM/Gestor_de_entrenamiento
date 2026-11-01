<?php

session_start();

require '../comunes/auxiliar.php';
$pdo = conectar();
$email = obtener_email();
$id = obtener_id_usuario($pdo, $email);

$filas = obtener_actividades_usuario($pdo, $id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades del usuario</title>
</head>
<body id="body_actividad">
    <header id="cabecera_actividad"></header>
    <main id="principal_actividad">
        <?php if (!empty($filas)): ?>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Titulo</th>
                    <th>Ejercicio</th>
                    <th>Fecha</th>
                    <th>Calorias</th>
                </tr>
            </thead>
            <?php foreach ($filas as $fila) { ?>
            <tr>
                <td><?= hh($fila['nombre']) ?></td>
                <td><?= hh($fila['titulo']) ?></td>
                <td><?= hh($fila['ejercicio']) ?></td>
                <td><?= hh($fila['fecha']) ?></td>
                <td><?= hh($fila['calorias']) ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php else : ?>
            <h2>No hay actividades registradas de este usuario.</h2>
        <?php endif; ?>
        <a href="inicio.php">Volver</a>
    </main>
</body>
</html>