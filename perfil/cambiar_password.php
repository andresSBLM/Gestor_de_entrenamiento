<?php 

session_start();

require '../comunes/auxiliar.php';

comprobar_sesion();

$email = obtener_email();
$pdo = conectar();

$password = '';
$nueva_password = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = obtener_post('password');
    $nueva_password = obtener_post('nueva_password');
    validar_password($password, $errores);
    validar_password($nueva_password, $errores);

    $password_usuario = obtener_password_usuario($pdo, $email);
    if ($password_usuario === false) {
        $errores['password'][] = 'Este usuario no tiene contraseña';
    } else {
        $password_usuario = $password_usuario['password'];
    }

    if (empty($errores) && password_verify( $password, $password_usuario)) {
        cambiar_password_usuario($pdo, $email, password_crip($nueva_password));
        mover_a('../entrada/acceder.php');
    } else {
        $errores['password'][] = 'Ha ocurrido un error';
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar de contraseña</title>
</head>
<body id="body_cambiar_password">
    <header id="cabecera_cambiar_password">
        <h1>Cambiar de contraseña</h1>
        <p>Introduce tu anterior contraseña y la nueva que quieres teenr.</p>
    </header>
    <main id="principal_cambiar_password">
        <form action="cambiar_password.php" method="post">
            <label>
                Contraseña anterior:
                <input type="password" id="password" name="password">
            </label>
            <br>
            <label>
                Contraseña nueva:
                <input type="password" id="nueva_password" name="nueva_password">
            </label>
            <ul>
                    <?php foreach ($errores['password'] ?? [] as $error): ?>
                    <li><?= $error?></li> 
                    <?php endforeach; ?> 
            </ul>
            <br>
            <button type="submit">Cambiar contraseña</button><br>
            <a href="modificar_perfil.php">Volver</a>
        </form>
    </main>
</body>
</html>