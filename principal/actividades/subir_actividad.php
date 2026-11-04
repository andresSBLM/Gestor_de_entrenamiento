<?php

session_start();

require '../../comunes/auxiliar.php';

comprobar_sesion();

$pdo = conectar();



$ejercicios = todos_los_ejercicios($pdo);
$actividad = $_GET['ejercicio'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = obtener_email();
    $id = obtener_id_usuario($pdo, $email); 
    $actividad = obtener_post('actividad');
    $atributos = conseguir_atributos_ejercicio($actividad);
    compact($atributos);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir nueva actividad</title>
</head>
<body>
    <header>
        <h1>Subir actividad</h1>
    </header>
    <p>Elige el ejercicio que has realizado y apunta sus resultados</p>
    <form action="subir_actividad.php">
        <select name="ejercicio" id="ejercicio">
            <?php    
            if (!empty($ejercicios)) {
                
                foreach ($ejercicios as $ejercicio):    
                    ?>
                <?php 
                if ($ejercicio['nombre'] == $actividad) {
                    ?> <option selected value="<?= hh($ejercicio['nombre'])?>"><?= hh($ejercicio['nombre'])?></option>
                <?php } else {
                    ?> <option value="<?= hh($ejercicio['nombre'])?>"><?= hh($ejercicio['nombre'])?></option>
                <?php }
                endforeach;
            } else {
                ?> <h2>No hay ejercicios disponibles</h2> <?php
            } 
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                ?><option value="" selected></option><?php
            }
            ?>
        </select>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && $actividad) {
        $atributos = conseguir_atributos_ejercicio($actividad);
        if ($atributos == []) {
            ?><h2>Actividad no registrada</h2> <?php
        } else {
            ?>
            <form action="subir_actividad.php" method="post">
                <?php
            foreach ($atributos as $atributo) {
                ?>
                <label>
                    <?= hh($atributo) ?>
                    <input type="number" id="<?= $atributo ?>" name="<?= $atributo?>" min="0" required>
                </label>
                <br>
                <?php } ?>
                <input type="hidden" id="actividad" name="actividad">
                <button type="submit">Enviar</button>
            </form>
            <?php 
        }
    }

    ?>
    <a href="../inicio.php">Volver</a>
    <script>
        const EJERCICIO = document.getElementById("ejercicio");
        EJERCICIO.addEventListener("change", () => {
            window.location.href = `subir_actividad.php?ejercicio=${EJERCICIO.value}`;
        });
    </script>
</body>
</html>