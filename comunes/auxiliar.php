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
        <a href="../perfil/perfil.php">Perfil</a>
        <a href="../principal/actividad.php">Actividad</a>
        <a href="../entrada/acceder.php">Cambiar de usuario.</a>
        <a href="../entrada/cerrar_sesion.php">Cerrar sesión</a>
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

function obtener_id_usuario(PDO $pdo, string $email): int {
    $sent = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
    $sent->execute([':email' => $email]);
    return (int) $sent->fetchColumn();
}


function obtener_actividades_usuario(PDO $pdo, string $id) : array
{
    $sent = $pdo->prepare('
    SELECT 
    p.id as id,
    u.nombre as nombre,
    p.titulo as titulo,
    e.nombre as ejercicio, 
    p.fecha as fecha,
    p.calorias as calorias
    FROM usuarios u join practicas p on p.usuario_id = u.id 
                    join ejercicios e on e.id = p.ejercicio_id
    WHERE u.id = :id
    ORDER BY p.fecha DESC
    ');
    $sent->execute([':id' => $id]);
    return $sent->fetchAll();
}

/*Devuelve el email de la sesión actual */
function obtener_email() : string{
    return $_SESSION['login'];
}



function todo_sobre_usuario(PDO $pdo, string $email) {
    $sent = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
    $sent->execute([':email'=> $email]);
    return $sent->fetch();
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

function validar_apellidos(string $apellidos, &$errores) :void
{
    if($apellidos == '') {
        $errores['apellidos'][] = 'El apellido está vacío.';
    } elseif (mb_strlen($apellidos) >= 255) {
        $errores['apellidos'][] = 'El apellido es demasiado largo.';
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

function convertir_peso(string $peso, array &$errores) :void
{
    if($peso == '') {
        $errores['peso'][] = 'El peso está vacío';
    } elseif (!filter_var($peso, FILTER_VALIDATE_FLOAT)) {
        $errores['peso'][] = 'El peso debe de ser un número entero o decimal';
    }  elseif ($peso >= 500 || $peso <= 5 ) {
        $errores['peso'][] = 'El peso no entra en el rango humano';
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
        $errores['confirm_password'][] = 'Correo electrónico o contraseña incorrecta.';
    }
}

function insertar_usuario(PDO $pdo, array $campos): void {
    extract($campos);
    $sent = $pdo->prepare('INSERT INTO usuarios (nombre, apellidos, edad, peso, email, password ) 
                          VALUES (:nombre, :apellidos, :edad, :peso , :email, :password)');
    $sent->execute([
        ':nombre' => $nombre,
        ':apellidos' => $apellidos,
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
    $sent = $pdo->prepare("SELECT email 
                            FROM usuarios 
                            WHERE categoria_id = (SELECT id FROM categorias WHERE categoria = 'admin')");
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

function obtener_ejercicios_usuario(PDO $pdo, int $id): array {
    $sent = $pdo->prepare('
        SELECT DISTINCT e.nombre AS ejercicio
        FROM practicas p
        JOIN ejercicios e    ON e.id = p.ejercicio_id
        WHERE p.usuario_id = :id
        ORDER BY e.nombre
    ');
    $sent->execute([':id' => $id]);
    return $sent->fetchAll();
}









/*Panel de evolución */

function obtener_mes_anterior():string {
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
          'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $mes = (int) date('n') - 1;
    if ($mes === 0) {
        $mes = 12;
    }
    return $meses[$mes];
}

function obtener_dias_mes_anterior():string {
    return (int) (new DateTime('first day of last month'))->format('t');
}

function obtener_mes_actual():string {
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
          'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $mes = $meses[(int) date('n')];
    return $mes;
}

function obtener_dias_mes_actual():string {
    return date('t');
}  

function obtener_fecha_actual():string {
    return date('Y-m-d');
}

function obtener_fecha_inicio(): string {
    $a = new DateTime('first day of last month');
    $b = $a->format('Y-m-d');
    return $b;
}

function obtener_ano_mes_actual():string {
    return date('Y-m');
}

function obtener_ano_mes_anterior() :string{
    $f = new DateTime('first day of last month');

    return $f->format('Y-m');
}

function obtener_fechas_usuario(PDO $pdo, int $id, string $fecha_inicio, string $fecha_actual): array {
    $sent = $pdo->prepare('
    SELECT DISTINCT
        p.fecha as fecha
    FROM usuarios u join practicas p on u.id = p.usuario_id
    WHERE u.id = :id and fecha BETWEEN :fecha_inicio and :fecha_actual;
    ');
    $sent->execute([
        ':id'=> $id,
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_actual'=> $fecha_actual
        ]);
    return $sent->fetchAll(PDO::FETCH_COLUMN);
}











/* Panel de Perfil */


function datos_usuario(PDO $pdo, string $email):array {
    $sent = $pdo->prepare('
    SELECT 
        u.nombre as nombre,
        u.apellidos as apellidos,
        u.edad as edad,
        u.peso as peso,
        u.email as email,
        c.categoria as categoria
    FROM usuarios u join categorias c on u.categoria_id = c.id
    WHERE u.email = :email');
    $sent->execute([':email'=> $email]);
    return $sent->fetch();
}






/*Panel de modificar_perfil */

function comprobar_email(PDO $pdo, string $email, string $email_propio, array &$errores): void {
    $sent = $pdo->prepare('SELECT 1 FROM usuarios WHERE (email ilike :email AND email != :email_propio)');
    $sent->execute([':email'=> $email, ':email_propio'=> $email_propio]);
    if ($sent->fetch() !== false) {
        $errores['email'] = 'No se puede utilizar este email';
    }
}



function modificar_usuario(PDO $pdo,  string $id, array $campos) :void {
    extract($campos);
    $sent = $pdo->prepare('
    UPDATE usuarios
        SET nombre = :nombre,
            apellidos = :apellidos,
            edad = :edad,
            peso = :peso,
            email = :email
        WHERE id = :id;
    ');
    $sent->execute([
        ':nombre'=> $nombre,
        ':apellidos' => $apellidos,
        ':edad' => $edad,
        ':peso' => $peso,
        ':email' => $email,
        ':id' => $id
    ]);
}


/*Panel cambiar password */

function obtener_password_usuario(PDO $pdo, string $email): array {
    $sent = $pdo->prepare('SELECT password FROM usuarios WHERE email = :email');
    $sent->execute([':email'=> $email]);
    return $sent->fetch();
}


function cambiar_password_usuario(PDO $pdo, string $email, string $password) {
    $sent = $pdo->prepare('UPDATE usuarios 
                            SET password = :password
                            WHERE email = :email');
    $sent->execute([':email'=> $email,':password'=> $password]);
}











/*Panel de actividades */

function borrar_actividad(PDO $pdo, string $id_actividad):void {
    $sent = $pdo->prepare('DELETE FROM practicas WHERE id = :id');
    $sent->execute(['id'=> $id_actividad]);
}


function todos_los_ejercicios(PDO $pdo) :array {
    $sent = $pdo->prepare('SELECT * FROM ejercicios');
    $sent->execute();
    return $sent->fetchAll();
}

function conseguir_atributos_ejercicio(string $ejercicio) :array {
    switch ($ejercicio) {
        case 'dominada':
        case 'remo':
        case 'flexiones':
        case 'flexiones declinadas':
        case 'flexiones en pica':   
        case 'abdominales':
        case 'abdominales en caida':
        case 'abdominales en barra':
            return ['Repeticiones', 'Series', 'Descanso entre series (segundos)'];
        case 'sentadillas':
        case 'sentadillas a 1 pie':
        case 'pesas':
            return ['Repeticiones', 'Series', 'Peso (Kg)', 'Descanso entre series (segundos)'];
        case 'ciclismo':
            return ['Distancia (Km)', 'Tiempo (min)', 'Pausas'];
        case 'atletismo':
            return ['Distancia (Km)', "Series", "Duracion (min)"];
        default :
            return [];
    };
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
    $sent = $pdo->prepare('
    SELECT 
        u.*, 
        c.categoria
    FROM usuarios u JOIN categorias c ON c.id = u.categoria_id'
                    
    );
    $sent->execute();
    return $sent->fetchAll();
}






