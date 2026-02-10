<?php
/* Archivo controlador para agregar un producto al carrito del usuario */

/* Inicia sesion y carga dependencias necesarias para autoload y conexion a base de datos */
session_start();
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Obtiene id de usuario desde la sesion y id de producto desde la peticion POST */
$idUsuario = $_SESSION['id'];
$idProducto = $_POST['id'];

/* Busca el producto por id usando el modelo Producto y redirige a la raiz si no existe */
$producto = Producto::findById($idProducto);
if (!$producto) {
    header("Location: ../index.php");
    exit;
}

/* Prepara y ejecuta una consulta para verificar si el producto ya esta en el carrito del usuario */
$checkStmt = $conn->prepare(
    "SELECT id FROM carrito WHERE usuario_id = ? AND producto_id = ?"
);
$checkStmt->bind_param("ii", $idUsuario, $idProducto);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

/* Si la consulta devuelve filas entonces el producto ya esta en el carrito y se redirige con error */
if ($checkResult->num_rows > 0) {
    /* Redirige a la vista del carrito indicando que el producto ya esta en el carrito */
    header("Location: ver.php?error=ya_en_carrito");
    exit;
} else {
    /* Si no existe el registro en carrito inserta una fila nueva con cantidad 1 usando consulta preparada */
    $insertStmt = $conn->prepare(
        "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)"
    );
    $insertStmt->bind_param("ii", $idUsuario, $idProducto);
    $insertStmt->execute();

    /* Redirige a la vista del carrito indicando exito con el parametro success anadido */
    header("Location: ver.php?success=añadido");
    exit;
}
?>