<?php 
session_start();

require '../comunes/auxiliar.php';

$email = obtener_email();
$pdo = conectar();
$campos = datos_usuario($pdo, $email);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
    <link rel="stylesheet" href="../css/style">
</head>
<body>
    <header>
        <h1>Perfil del usuario</h1>
    </header>
    <main>
        <h2><?= hh($campos['nombre'])." ".hh($campos['apellidos']) ?></h2>
        <p><?= hh($campos['edad'])." años." ?></p>
        <p><?= (hh($campos['peso'])/1000)." Kilogramos." ?></p>
        <p><?= hh($campos['email']) ?></p>
        <p><?= "Categoria: ".hh($campos['categoria']) ?></p>
        <a href="../principal/inicio.php">Volver</a>
    </main>
</body>
</html>