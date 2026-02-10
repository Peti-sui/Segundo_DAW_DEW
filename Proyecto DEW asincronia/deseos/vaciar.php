<?php
session_start();
require_once '../config/autoload.php';
require_once '../config/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario_id = $_SESSION['id'];

/* Eliminar todos los items de la lista de deseos del usuario */
$conn->query("DELETE FROM lista_deseos WHERE usuario_id = $usuario_id");

header("Location: ver.php?success=vaciado");
exit;
?>