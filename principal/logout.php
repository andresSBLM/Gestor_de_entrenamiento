<?php 
require '../comunes/auxiliar.php';
session_start();

$email = $_SESSION['login'];

$pdo = conectar();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (mb_strtoupper(obtener_post('confirmar')) == 'SI') {
        borrar_usuario($pdo, $email);
        $_SESSION = [];
        session_destroy();
        mover_a('../index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacer logout.</title>
</head>
<body>
    <form action="logout.php" method="post">
        <label>
            Introduce "SI" si estas seguro de querer cerrar sesión: <br>
            <input type="text" required id="confirmar" name="confirmar">
        </label>
        <button type="submit">Enviar</button><br>
        <a href="inicio.php">Volver</a>
    </form>
</body>
</html>
