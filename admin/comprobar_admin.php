<?php

require '../comunes/auxiliar.php';
$pdo = conectar();
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = obtener_post('password');
    $email = obtener_email();
    if (buscar_admin($pdo, $email, $password)) {
        mover_a('admin.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobacion de admin</title>
</head>
<body>
    <form action="comprobar_admin.php" method="post">
        <label>
            Contraseña:
            <input type="password" name="password" id="password"><br>
        </label>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>