<?php 

require '../auxiliar.php';

session_start();


if (!isset($_SESSION['login'])) 
{
    $errores = [];
    $nombre = '';
    $edad = '';
    $peso = '';
    $email = '';
    $password = '';
    $confirm_password = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $nombre = obtener_post('nombre');
        $edad = obtener_post('edad');
        $peso = obtener_post('peso');
        $unidad_peso = obtener_post('unidad_peso');
        $email = obtener_post('email');
        $password = obtener_post('password');
        $confirm_password = obtener_post('confirm_password');

        validar_nombre($nombre, $errores);
        validar_edad($edad, $errores);
        convertir_peso($peso, $unidad_peso, $errores);
        validar_email($email, $errores);
        validar_password($password, $errores);
        comparar_password($password, $confirm_password, $errores);
        $pdo = conectar();
        buscar_usuario($pdo, $email, $errores);

        if (empty($errores)) {
            $_SESSION ['login'] = $email;
            $password_encriptada = password_crip($password);
            $campos = compact('nombre', 'edad', 'peso', 'email', 'password_encriptada');
            insertar_usuario($pdo, $campos);
            mover_a('/entrada/inicio.php');
        }
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de logueo.</title>
</head>
<body>
    <h1>Campo de Registro</h1>
    <form action="/entrada/index.php" method="post">
        <label>
            Nombre:
            <input type="text" id="nombre" name="nombre" value="<?= hh($nombre) ?>"><br>
            <ul>

                <?php foreach ($errores['nombre'] ?? [] as $error) {
                    ?><li><?=$error?></li><?php
                }?>
            </ul>
        </label>
        <label>
            Edad:
            <input type="number" id="edad" name="edad" value="<?= hh($edad) ?>"><br>
            <ul>
                <?php foreach($errores['edad'] ?? [] as $error) {
                    ?> <li><?= $error ?></li> <?php
                } ?>
            </ul>
        </label>
        <label>
            Peso:
            <input type="number" id="peso" name="peso" value="<?= hh($peso) ?>">
            <select name="unidad_peso" id="unidad_peso">
                <option value="kg">Kilogramos</option>
                <option value="g">Gramos</option>
            </select>
            <br>
            <ul>
                <?php foreach($errores['peso'] ?? [] as $error) {
                    ?> <li><?= $error ?></li> <?php
                } ?>
            </ul>
        </label>
        <label>
            Email:
            <input type="text" id="email" name="email" value="<?= hh($email) ?>"><br>
            <ul>
                <?php foreach($errores['email'] ?? [] as $error) {
                    ?> <li><?= $error ?></li> <?php
                } ?>
            </ul>
        </label>
        <label>
            Contraseña:
            <input type="password" id="password" name="password"><br>
            <ul>
                <?php foreach($errores['password'] ?? [] as $error) {
                    ?> <li><?= $error ?></li> <?php
                } ?>
            </ul>
        </label>
        <label>
            Confirma la Contraseña:
            <input type="password" id="confirm_password" name="confirm_password"><br>
            <ul>
                <?php foreach($errores['confirm_password'] ?? [] as $error) {
                    ?> <li><?= $error ?></li> <?php
                } ?>
            </ul>
        </label>
        <button type="submit">Iniciar sesión</button>
    </form>
    <a href="/entrada/acceder.php">Entrar</a>
</body>
</html>

<?php 

} else {
    cabecera();
}

?>