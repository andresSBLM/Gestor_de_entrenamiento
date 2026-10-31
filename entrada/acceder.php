<?php 

require '../auxiliar.php';

session_start();
$email = '';
$pdo = conectar();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtener_post('email');
    $password = obtener_post('password');

    if (comprobar_usuario($pdo, $email, $password)) {
        $_SESSION['login'] = $email;
        mover_a('inicio.php');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona de acceso</title>
</head>
<body>
    <form action="acceder.php" method="post">
        <label>
            Correo electrónico:
            <input type="text" id="email" name="email" value="<?= hh($email) ?>"><br>
        </label>
        <label>
            Contraseña:
            <input type="password" id="password" name="password">
        </label>
        <button type="submit">Enviar</button>
        <a href="/entrada/index.php">Volver</a>
    </form>
</body>
</html>