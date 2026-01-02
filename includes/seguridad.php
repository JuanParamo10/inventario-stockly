<?php
session_start();
// Si no existe la variable user_id, es que no ha pasado por el login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>