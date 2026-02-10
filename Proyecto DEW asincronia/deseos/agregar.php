<?php
/* Archivo que gestiona anadir un producto a la lista de deseos  𐙚ᚖ */
session_start();
/* Incluir el autoload para cargar clases y la configuracion de base de datos  𐌗𐙚 */
require_once '../config/autoload.php';
require_once '../config/db.php';

/* Comprobar que el usuario esta autenticado en la sesion y redirigir si no  𐙚ꙮ */
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* Obtener el id del usuario desde la sesion y el id del producto desde el formulario POST  𐌗ᚗ */
$usuario_id = $_SESSION['id'];
$producto_id = $_POST['id'];

/* Comprobar que el producto existe usando el modelo Producto  𐙚ᚖ */
$producto = Producto::findById($producto_id);
if (!$producto) {
    /* Si no se encuentra el producto redirigir a la pagina principal  𐌗𐙚 */
    header("Location: ../index.php");
    exit;
}

/* Preparar y ejecutar consulta para verificar si el producto ya esta en la lista de deseos del usuario  𐙚ᚗ */
$checkStmt = $conn->prepare(
    "SELECT id FROM lista_deseos WHERE usuario_id = ? AND producto_id = ?"
);
$checkStmt->bind_param("ii", $usuario_id, $producto_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

/* Si la consulta devuelve filas significa que el producto ya esta en la lista de deseos  𐌗𐙚 */
if ($checkResult->num_rows > 0) {
    /* Redirigir a la pagina de vista indicando que ya esta en deseos  𐙚ᚖ */
    header("Location: ver.php?error=ya_en_deseos");
    exit;
} else {
    /* Preparar la insercion para anadir el producto a la lista de deseos  𐙚ᚗ */
    $insertStmt = $conn->prepare(
        "INSERT INTO lista_deseos (usuario_id, producto_id) VALUES (?, ?)"
    );
    $insertStmt->bind_param("ii", $usuario_id, $producto_id);
    $insertStmt->execute();

    /* Redirigir a la pagina de vista indicando exito en la adicion  𐌗𐙚 */
    header("Location: ver.php?success=añadido");
    exit;
}
?>