<?php

//Funciones globales a todos los archivos
function conectar() :PDO {
    return new PDO("pgsql:host=localhost;dbname=entrenamiento", "entrenamiento", "entrenamiento");
}

function mover_a(string $lugar) :void
{
    header("Location: $lugar");
    exit;
}

function hh(string $str) : string
{
    return htmlspecialchars($str);
}

function opciones($email):void {
    
    ?>
    <div id="opciones">
        <a href="../principal/perfil.php">Perfil</a>
        <a href="../principal/actividad.php">Actividad</a>
        <a href="../entrada/acceder.php">Cambiar de usuario.</a>
    <?php
    if ($email !== obtener_correo_admin(conectar())) {
    ?>
        <a href="../principal/logout.php">Logout</a>
    <?php
    }
    ?></div><?php
    
}

function password_crip(string $password) :string {
    return password_hash($password, PASSWORD_DEFAULT);
}

function comprobar_sesion() :void {

    if (!isset($_SESSION['login'])) {
        mover_a('../index.php');
    }

    $sent = conectar()->prepare('SELECT 1 FROM usuarios WHERE email = :email');
    $sent->execute([':email' => $_SESSION['login']]);

    if (!$sent->fetchColumn()) {
        $_SESSION = [];
        session_destroy();
        mover_a('../index.php');
    }
}










//Funciones propias de index.php

function obtener_post(string $par) : string
{
    return $_POST[$par] ??"";
}

function validar_nombre(string $nombre, &$errores) :void
{
    if($nombre == '') {
        $errores['nombre'][] = 'El nombre está vacío.';
    } elseif (mb_strlen($nombre) >= 255) {
        $errores['nombre'][] = 'El nombre es demasiado largo.';
    }
}

function validar_edad(string $edad, &$errores) :void
{
    if($edad == '') {
        $errores['edad'][] = 'La edad está vacía.';
    } elseif(!ctype_digit($edad)) {
        $errores['edad'][] = 'La edad debe de ser un número';
    } elseif ($edad >= 120 || $edad <= 0) {
        $errores['edad'][] = 'La edad no entra en el rango humano.';
    }
}

function convertir_peso(string &$peso, string $unidad_peso, array &$errores) :void
{
    if($peso == '') {
        $errores['peso'][] = 'El peso está vacío';
    } elseif (!filter_var($peso, FILTER_VALIDATE_INT)) {
        $errores['peso'][] = 'El peso debe de ser un número entero o decimal';
    }  elseif (($peso >= 500 && $unidad_peso == 'kg') || ($peso <= 5 && $unidad_peso == 'kg')) {
        $errores['peso'][] = 'El peso no entra en el rango humano';
    } elseif (($peso >= 500000 && $unidad_peso == 'g') || ($peso <= 5000 && $unidad_peso == 'g')) {
        $errores['peso'][] = 'El peso no entra en el rango humano';
    } elseif ($unidad_peso == 'Kg') {
        $peso *= 1000;
    }
}

function validar_email(string $email, array &$errores) : void 
{
    if ($email == '') {
        $errores['email'][] = 'El email está vacío.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'][] ='La sintaxis del email no es válida.';
    } elseif (mb_strlen(trim($email)) > 255) {
        $errores['email'][] = 'El email es demasiado largo.';
    }
}

function validar_password(string $password, array &$errores) : void 
{
    if ($password == '') {
        $errores['password'][] = 'La contraseña no puede ser vacía.';
    } elseif (mb_strlen(trim($password)) > 255) {
        $errores['password'][] = 'La contraseña es demasiado larga.';
    }
}

function comparar_password(string $password,  string $confirm_password, array &$errores) : void
{
    if ($password !== $confirm_password) {
        $errores['confirm_password'][] = 'Las contraseñas no coinciden.';
    }
}

function buscar_usuario(PDO $pdo, string $email, &$errores):void {
    $sent = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
    $sent->execute([':email' => $email]);
    if ($sent->rowCount() > 0) {
        $errores['confirm_password'][] = 'El usuario ya está registrado.';
    }
}

function insertar_usuario(PDO $pdo, array $campos): void {
    extract($campos);
    $sent = $pdo->prepare('INSERT INTO usuarios (nombre, edad, peso, email, password ) 
                          VALUES (:nombre, :edad, :peso , :email, :password)');
    $sent->execute([
        ':nombre' => $nombre,
        ':edad' => $edad,
        ':peso' => $peso,
        ':email' => $email,
        ':password' => $password_encriptada
    ]);
}

function borrar_usuario(PDO $pdo, string $email) 
{
    $sent = $pdo->prepare('DELETE FROM usuarios WHERE email = :email');
    $sent->execute([':email' => $email]);
}









/*Panel de inicio */

function obtener_correo_admin(PDO $pdo) {
    $sent = $pdo->prepare("SELECT email FROM usuarios WHERE puesto like 'admin'");
    $sent->execute();
    return $sent->fetchColumn();
}

function buscar_admin(PDO $pdo, string $email, string $password):bool {
    if ($email == obtener_correo_admin($pdo)) {
        $sent = $pdo->prepare("SELECT password FROM usuarios WHERE email = :email");
        $sent->execute([':email' => $email]);
        $hash = $sent->fetchColumn();
        return $hash !== false && password_verify($password, $hash);
    } return false;
}

/*Panel de acceder */

function comprobar_usuario(PDO $pdo, string $email, string $password): bool {
    $sent = $pdo->prepare('SELECT password FROM usuarios WHERE email = :email');
    $sent->execute([':email' => $email]);
    $hash = $sent->fetchColumn();

    return $hash !== false && password_verify($password, $hash);
}








/*Panel de administración */

function buscar_todos_los_usuarios(PDO $pdo) :array{
    $sent = $pdo->prepare('SELECT * from usuarios');
    $sent->execute();
    return $sent->fetchAll();
}



