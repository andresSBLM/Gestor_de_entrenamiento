<?php 

session_start();

require '../comunes/auxiliar.php';

$email = $_POST["email"];

$pdo = conectar();
$sent = $pdo->prepare("DELETE FROM usuarios WHERE email = :email");
$sent->execute(array(":email"=> $email));

mover_a('admin.php');