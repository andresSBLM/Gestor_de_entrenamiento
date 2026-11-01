<?php 
require '../comunes/auxiliar.php';
session_start();

$email = $_SESSION['login'];

$pdo = conectar();

borrar_usuario($pdo, $email );

$_SESSION = [];

mover_a('../index.php');

