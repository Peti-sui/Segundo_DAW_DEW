<?php
/* Cierra la sesion y redirige */
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
