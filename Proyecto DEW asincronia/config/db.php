
<?php
/* Conexion con la base de datos */
$conn = new mysqli("localhost", "root", "", "tienda_llaveros");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>