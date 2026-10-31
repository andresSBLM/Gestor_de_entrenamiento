<?php

require 'auxiliar.php';

session_start();

$pdo = conectar();

$filas = buscar_todos_los_usuarios($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administrador</title>
</head>
<body>
    <h1>Bienvenido Administrador.</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Peso</th>
                <th>Email</th>
                <th>Puesto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <?php foreach ($filas as $fila) {
            ?> 
        <form action="borrar.php" method="post">
            <tr>
                <td><?= $fila['nombre'] ?></td>
                <td><?= $fila['edad'] ?></td>
                <td><?= $fila['peso'] ?></td>
                <td><?= $fila['email'] ?></td>
                <td><?= $fila['puesto']?></td>
                <?php if ($fila['puesto'] !== 'admin') {
                    ?><td><button type="submit">Borrar</button></td>
                <?php } ?>
                <input type="hidden" name="email" id="email" value="<?= $fila['email'] ?>"  >
            </tr>
        </form>
        <?php } ?>
    </table>
    <a href="inicio.php">Volver</a>
</body>
</html>