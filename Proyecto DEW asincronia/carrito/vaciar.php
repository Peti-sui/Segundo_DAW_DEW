<?php
/* Script para vaciar el carrito del usuario logueado y redirigir a la vista ver php
   Solo se agregan comentarios al codigo existente sin modificar la logica
*/

session_start();

require_once '../config/db.php';

$id = $_SESSION['id'];

$conn->query("DELETE FROM carrito WHERE usuario_id = $id");

header("Location: ver.php");

exit;
?>