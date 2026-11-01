<?php

session_start();
require '../comunes/auxiliar.php';

$_SESSION = [];

session_destroy();

mover_a('../index.php');