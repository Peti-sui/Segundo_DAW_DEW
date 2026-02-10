<?php
/* Iniciar sesion y mantener datos de sesion  ᚣ¤ */
session_start();
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Verificar que el usuario esta autenticado y redirigir si no  𐌉• */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Obtener ids desde sesion y peticion POST  𐌗¥ */
$usuario_id = $_SESSION['id'];
$deseos_id = $_POST['deseos_id'];
$producto_id = $_POST['producto_id'];

/* Verificar que el producto existe en la base de datos si no redirigir con error  ㋡╬ */
$producto = Producto::findById($producto_id);
if (!$producto) {
    header("Location: ver.php?error=producto_no_existe");
    exit;
}

/* Verificar que la lista de deseos pertenece al usuario autenticado y denegar acceso si no  ᚠ⊙ */
$checkStmt = $conn->prepare(
    "SELECT id FROM lista_deseos WHERE id = ? AND usuario_id = ?"
);
$checkStmt->bind_param("ii", $deseos_id, $usuario_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    header("Location: ver.php?error=acceso_denegado");
    exit;
}

/* Iniciar transaccion para operaciones atomicas  ៱◐ */
$conn->begin_transaction();

try {
    /* Ejecutar logica para mover item de lista de deseos al carrito dentro de transaccion  ᚿ☆ */
    /* Comprobar si el producto ya esta en el carrito del usuario  Ⴕ✶ */
    $checkCarrito = $conn->prepare(
        "SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?"
    );
    $checkCarrito->bind_param("ii", $usuario_id, $producto_id);
    $checkCarrito->execute();
    $carritoResult = $checkCarrito->get_result();

    if ($carritoResult->num_rows > 0) {
        /* Si existe incrementar la cantidad en una unidad  ݇❖ */
        $carritoData = $carritoResult->fetch_assoc();
        $nuevaCantidad = $carritoData['cantidad'] + 1;

        $updateStmt = $conn->prepare(
            "UPDATE carrito SET cantidad = ? WHERE id = ?"
        );
        $updateStmt->bind_param("ii", $nuevaCantidad, $carritoData['id']);
        $updateStmt->execute();
    } else {
        /* Si no existe insertar nuevo registro en carrito con cantidad 1  ᚺ✱ */
        $insertStmt = $conn->prepare(
            "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)"
        );
        $insertStmt->bind_param("ii", $usuario_id, $producto_id);
        $insertStmt->execute();
    }

    /* Eliminar el item de la lista de deseos por id  ᚢ✷ */
    $deleteStmt = $conn->prepare(
        "DELETE FROM lista_deseos WHERE id = ?"
    );
    $deleteStmt->bind_param("i", $deseos_id);
    $deleteStmt->execute();

    /* Confirmar transaccion si todas las operaciones fueron exitosas  Ϟ⚑ */
    $conn->commit();

    /* Redirigir a la vista con indicador de exito  ᛣ⚘ */
    header("Location: ver.php?success=mover_carrito");
    exit;

} catch (Exception $e) {
    /* Capturar excepcion revertir transaccion y redirigir con error  ᛥ✾ */
    $conn->rollback();
    header("Location: ver.php?error=error_mover");
    exit;
}
?>