<?php

session_start();

require '../comunes/auxiliar.php';

comprobar_sesion();

$email = obtener_email();
$pdo = conectar();
$id = obtener_id_usuario($pdo, $email);



$filas = todo_sobre_usuario($pdo, $email);
$nombre = $filas['nombre'];
$apellidos = $filas['apellidos'];
$edad = $filas['edad'];
$peso = $filas['peso'];
$unidad_peso = 'kg';
$errores = [];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = obtener_post('nombre');
    $apellidos = obtener_post('apellidos');
    $edad = obtener_post('edad');
    $peso = obtener_post('peso');
    $email = obtener_post('email');

    validar_nombre($nombre, $errores);
    validar_apellidos($apellidos, $errores);
    validar_edad($edad, $errores);
    convertir_peso($peso, $errores);
    validar_email($email, $errores);
    comprobar_email($pdo, $email, obtener_email(), $errores);
    $pdo = conectar();

    if (empty($errores)) {
        $campos = compact('nombre', 'apellidos', 'edad', 'peso', 'email');
        modificar_usuario($pdo, $id, $campos);
        $_SESSION['login'] = $email;
        mover_a('perfil.php');
    }
}


if (empty($filas)) {
    ?>
    <h1>No se han encontrado datos de este usuario.</h1>
    <br><a href="perfil.php">Volver</a><?php
} else { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar usuario</title>
    </head>

    <body id="body_modificar_perfil">
        <header>
            <h1>Modificar perfil</h1>
            <p>La información dentro de los campos serán sus nuevos datos de perfil</p>
        </header>
        <main id="principal_modificar_usuario">
            <form action="modificar_perfil.php" method="post">
                <label>
                    Nombre:
                    <input type="text" id="nombre" name="nombre" value="<?= hh($nombre) ?>"><br>
                    <ul>

                        <?php foreach ($errores['nombre'] ?? [] as $error) {
                            ?>
                            <li>
                                <?= $error ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </label>
                <label>
                    Apellidos:
                    <input type="text" id="apellidos" name="apellidos" value="<?= hh($apellidos) ?>"><br>
                    <ul>

                        <?php foreach ($errores['apellidos'] ?? [] as $error) {
                            ?>
                            <li>
                                <?= $error ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </label>
                <label>
                    Edad:
                    <input type="number" id="edad" name="edad" value="<?= hh($edad) ?>"><br>
                    <ul>
                        <?php foreach ($errores['edad'] ?? [] as $error) {
                            ?>
                            <li>
                                <?= $error ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </label>
                <label>
                    Peso:
                    <input type="number" id="peso" step="0.01" name="peso" value="<?= hh($peso)?>">
                    <label>Kilogramos</label>
                    <br>
                    <ul>
                        <?php foreach ($errores['peso'] ?? [] as $error) {
                            ?>
                            <li>
                                <?= $error ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </label>
                <label>
                    Email:
                    <input type="text" id="email" name="email" value="<?= hh($email) ?>"><br>
                    <ul>
                        <?php foreach ($errores['email'] ?? [] as $error) {
                            ?>
                            <li>
                                <?= $error ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </label>
                <button type="submit">Modificar</button>
            </form>
            <a href="cambiar_password.php">Cambiar de contraseña.</a><br>
            <a href="perfil.php">Volver</a>
        </main>
    </body>
    </html>
<?php }
